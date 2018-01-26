/*画箭头，根据两个点*/
var getArrowPath = function (point1, point2) {
  let point3, point4, point5, point6, point7
  let [arrowLength, arrowWidth, lineWidth] = [10, 4, 1]
  let angle = Math.atan((point2[1] - point1[1]) / (point2[0] - point1[0]))

  point3 = [point2[0] + arrowWidth * Math.sin(angle), point2[1] - arrowWidth * Math.cos(angle)]
  point5 = [point2[0] - arrowWidth * Math.sin(angle), point2[1] + arrowWidth * Math.cos(angle)]
  if (point2[0] >= point1[0]) {
    point4 = [point2[0] + arrowLength * Math.cos(angle), point2[1] + arrowLength * Math.sin(angle)]
  } else {
    point4 = [point2[0] - arrowLength * Math.cos(angle), point2[1] - arrowLength * Math.sin(angle)]
  }
  point6 = [point2[0] - lineWidth * Math.sin(angle), point2[1] + lineWidth * Math.cos(angle)]
  point7 = [point1[0] - lineWidth * Math.sin(angle), point1[1] + lineWidth * Math.cos(angle)]
  return [point1, point2, point3, point4, point5, point6, point7]
}

var drawPic = function (ctx, items) {
  items.map(item => {
    ctx.save()
    if (item.type == "text") {
      if (item.font) {
        ctx.font = item.font
      }
      if (item.rotate) {
        ctx.translate(item.x, item.y)
        ctx.rotate(item.rotate * Math.PI / 180)
        ctx.fillText(item.text, 0, 0)
      } else {
        ctx.fillText(item.text, item.x, item.y)
      }
    } else if (item.type == "line") {
      ctx.beginPath()
      item.path.map((point, j) => {
        if (j == 0) {
          ctx.moveTo(point[0], point[1])
        } else {
          ctx.lineTo(point[0], point[1])
        }
      })
      ctx.strokeStyle = item.fill
      ctx.stroke()
    } else if (item.type == "path") {
      ctx.beginPath()
      item.path.map((point, j) => {
        if (j == 0) {
          ctx.moveTo(point[0], point[1])
        } else {
          ctx.lineTo(point[0], point[1])
        }
      })
      ctx.fillStyle = item.fill
      ctx.fill()
    } else if (item.type == "rect") {
      ctx.fillStyle = item.fill
      ctx.fillRect(item.x, item.y, item.width, item.height)
    } else if (item.type == "circle") {
      ctx.beginPath()
      ctx.fillStyle = item.fill
      ctx.arc(item.x, item.y, item.radius, 0, 2 * Math.PI, true) //2*Math.PI是360° 
      ctx.fill()
    }
    ctx.restore()
  })
}


//三比值法结果,参数为{'H2':1.1,'CH4':1.4,'C2H2':0.3,'C2H4':1.1,'C2H6':2.1}
var getTriRatioResult = function (data) {
  if (!data || data['H2'] * data['C2H4'] * data['C2H6'] == 0) {
    return {
      conclusion: '',
      instruction: ''
    }
  }
  var a = data['C2H2'] / data['C2H4']
  var b = data['CH4'] / data['H2']
  var c = data['C2H4'] / data['C2H6']
  var code = []

  if (a < 0.1) {
    code.push(0)
  } else if (a < 3) {
    code.push(1)
  } else {
    code.push(2)
  }

  if (b < 0.1) {
    code.push(1)
  } else if (b < 1) {
    code.push(0)
  } else {
    code.push(2)
  }

  if (c < 1) {
    code.push(0)
  } else if (c < 3) {
    code.push(1)
  } else {
    code.push(2)
  }

  var result = {
    conclusion: '正常',
    instruction: ''
  }
  if (code[0] == 0) {
    if (code[1] == 0 && code[2] == 1) {
      result.conclusion = "低温过热（低于150℃）"
      result.instruction = "绝缘导线过热，注意CO和CO2含量和CO2/CO值"
    } else if (code[1] == 2 && code[2] == 0) {
      result.conclusion = "低温过热（150-300）℃"
      result.instruction = "分接开关接触不良，引线夹件螺丝松动或接头焊接不良," + "涡流引起铜过热，铁芯漏磁，局部短路，层间绝缘不良，铁芯多点接等"
    } else if (code[1] == 2 && code[2] == 1) {
      result.conclusion = "中温过热（300-700）℃"
      result.instruction = "分接开关接触不良，引线夹件螺丝松动或接头焊接不良，" + "涡流引起铜过热，铁芯漏磁，局部短路，层间绝缘不良，铁芯多点接等"
    } else if (code[2] == 2) {
      result.conclusion = "高温过热（高于700℃）"
      result.instruction = "分接开关接触不良，引线夹件螺丝松动或接头焊接不良，" + "涡流引起铜过热，铁芯漏磁，局部短路，层间绝缘不良，铁芯多点接等"
    } else if (code[1] == 1 && code[2] == 0) {
      result.conclusion = "局部放电"
      result.instruction = "高湿度，高含量气量引起油中低能量密度的局部放电"
    }
  } else if (code[0] == 2) {
    if (code[1] == 2) {
      result.conclusion = "低能放电兼过热"
    } else {
      result.conclusion = "低能放电"
    }
    result.instruction = "引线对电位未固定的部件之间连续火花放电，分接抽头引线和油隙闪络，" + "不同电位之间的油中火花放电或悬浮电位之间的火花放电"
  } else if (code[0] == 1) {
    if (code[1] == 2) {
      result.conclusion = "电弧放电兼过热"
    } else {
      result.conclusion = "电弧放电"
    }
    result.instruction = "线圈匝间，层间短路，相间闪络，分接头引线间油隙闪络、引线对箱壳放电、" + "线圈熔断、分接开关飞弧、因环路电流引起电弧、引线对其他接地体放电等"
  }
  return result
}

class drawDavidTriangle {
  constructor(option) {
    this.option = {
      descAdjustX: 10,
      descAdjustY: 12,
      radius: 3,
      anglePhase: Math.PI / 3,
      graphicStartX: 50,
      graphicStartY: 50,
      descWidth: 100,
      descHeight: 15,
      descLineSpace: 25,
      descFont: '14px Arial',
      triangleLength: 220,
      colors: ['#7FFF00', '#B0E0E6', '#FA8072', '#808A87', '#228B22', '#FF8000', '#7FFFD4', 'red']
    }
    this.option.descStartX = this.option.graphicStartX + this.option.triangleLength * 1.2
    this.option.descStartY = this.option.graphicStartY
    this.pointLeft = [this.option.graphicStartX,
      this.option.graphicStartY + this.option.triangleLength * Math.sin(this.option.anglePhase)
    ]
    this.pointTop = [this.option.graphicStartX + this.option.triangleLength * 0.5,
      this.option.graphicStartY
    ]
    this.pointRight = [this.option.graphicStartX + this.option.triangleLength,
      this.option.graphicStartY + this.option.triangleLength * Math.sin(this.option.anglePhase)
    ]
  }
  convertPercentToPoint(percentCH4, percentC2H4) {
    var tmpY = this.pointLeft[1] - this.option.triangleLength * Math.sin(this.option.anglePhase) * percentCH4 / 100
    var tmpX = this.pointLeft[0] + percentCH4 / 100 * this.option.triangleLength * 0.5 + this.option.triangleLength * percentC2H4 / 100
    return [tmpX, tmpY]
  }

  getDavidResult(data) {
    var total = data['CH4'] + data['C2H4'] + data['C2H2']
    var a = data['CH4'] / total
    var b = data['C2H4'] / total
    var c = data['C2H2'] / total
    if (a > 0.98) {
      return 'PD—局部放电'
    } else if (b < 0.23 && c > 0.13) {
      return 'D1—低能放电'
    } else if ((b > 0.23 && b < 0.38 && c > 0.13) || (b > 0.38 && c > 0.29)) {
      return 'D2—高能放电'
    } else if (a < 0.98 && b < 0.1 && c < 0.04) {
      return 'T1—热程故障,t<300℃'
    } else if (b > 0.1 && b < 0.5 && c < 0.04) {
      return 'T2—热故障,300℃<t<700℃'
    } else if (b > 0.5 && c < 0.15) {
      return 'T3—热故障,t>700℃'
    } else {
      return 'D或者T故障'
    }
  }

  drawResult(canvas, data) {
    var total = data['CH4'] + data['C2H4'] + data['C2H2']
    if (!total) {
      return
    }
    var point = this.convertPercentToPoint(data['CH4'] * 100 / total, data['C2H4'] * 100 / total)
    var resultItems = [{
      type: 'circle',
      x: point[0],
      y: point[1],
      radius: this.option.radius,
      fill: this.option.colors[7]
    }]
    resultItems.push({
      type: 'text',
      x: this.option.descStartX,
      y: this.option.descStartY + this.option.descLineSpace * (7 + 1) + this.option.descAdjustY,
      text: '诊断结果 ',
      font: this.option.descFont
    }, {
      type: 'text',
      x: this.option.descStartX + this.option.descWidth + this.option.descAdjustX,
      y: this.option.descStartY + this.option.descLineSpace * (7 + 1) + this.option.descAdjustY,
      text: this.getDavidResult(data),
      font: this.option.descFont
    })
    var ctx = canvas.getContext("2d")
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    drawPic(ctx, resultItems)
  }
  InitTriangle(canvas) {
    var descContent = ['PD-局部放电', 'D1-低能放电', 'D2-高能放电', 'T1-热故障', 'T2-热故障', 'T3-热故障', 'D + T']
    var triangleitems = []
      //大卫三角形内部
      {
        var drawPoints = []

        //PD
        drawPoints.push(this.pointTop)
        drawPoints.push(this.convertPercentToPoint(98, 0))
        drawPoints.push(this.convertPercentToPoint(98, 2))
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[0]
        })

        //D1
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(100 - 13, 0))
        drawPoints.push(this.convertPercentToPoint(100 - 13 - 23, 23))
        drawPoints.push(this.convertPercentToPoint(0, 23))
        drawPoints.push(this.pointLeft)
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[1]
        })

        //D2
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(0, 23))
        drawPoints.push(this.convertPercentToPoint(100 - 13 - 23, 23))
        drawPoints.push(this.convertPercentToPoint(100 - 38 - 13, 38))
        drawPoints.push(this.convertPercentToPoint(100 - 38 - 29, 38))
        drawPoints.push(this.convertPercentToPoint(0, 100 - 29))
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[2]
        })

        //T1
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(100 - 4, 0))
        drawPoints.push(this.convertPercentToPoint(98, 0))
        drawPoints.push(this.convertPercentToPoint(98, 2))
        drawPoints.push(this.convertPercentToPoint(100 - 10, 10))
        drawPoints.push(this.convertPercentToPoint(100 - 10 - 4, 10))
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[3]
        })

        //T2
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(100 - 10, 10))
        drawPoints.push(this.convertPercentToPoint(100 - 10 - 4, 10))
        drawPoints.push(this.convertPercentToPoint(100 - 50 - 4, 50))
        drawPoints.push(this.convertPercentToPoint(100 - 50, 50))
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[4]
        })

        //T3
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(100 - 50, 50))
        drawPoints.push(this.convertPercentToPoint(100 - 50 - 15, 50))
        drawPoints.push(this.convertPercentToPoint(0, 100 - 15))
        drawPoints.push(this.pointRight)
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[5]
        })

        //D+T
        drawPoints = []
        drawPoints.push(this.convertPercentToPoint(100 - 4, 0))
        drawPoints.push(this.convertPercentToPoint(100 - 13, 0))
        drawPoints.push(this.convertPercentToPoint(100 - 38 - 13, 38))
        drawPoints.push(this.convertPercentToPoint(100 - 38 - 29, 38))
        drawPoints.push(this.convertPercentToPoint(0, 100 - 29))
        drawPoints.push(this.convertPercentToPoint(0, 100 - 15))
        drawPoints.push(this.convertPercentToPoint(100 - 50 - 15, 50))
        drawPoints.push(this.convertPercentToPoint(100 - 50 - 4, 50))
        triangleitems.push({
          type: 'path',
          path: drawPoints, //路径
          fill: this.option.colors[6]
        })
      }
      //大卫三角形坐标
      {
        var dotLinePercent = 2
        for (var i = 0; i < 6; i++) {
          var pointCH4 = this.convertPercentToPoint(20 * i, 0)
          var point2CH4 = this.convertPercentToPoint(20 * i, -dotLinePercent)
          var textLocate = this.convertPercentToPoint(20 * i, -15)
          triangleitems.push({
            type: 'line',
            path: [pointCH4, point2CH4], //路径
            fill: 'balck'
          }, {
            type: 'text',
            x: textLocate[0],
            y: textLocate[1],
            text: i * 20,
            rotate: 300
          })

          var pointC2H4 = this.convertPercentToPoint(100 - 20 * i, 20 * i)
          var point2C2H4 = this.convertPercentToPoint(100 - 20 * i + dotLinePercent, 20 * i)
          textLocate = this.convertPercentToPoint(100 - 20 * i + 5, 20 * i)
          triangleitems.push({
            type: 'path',
            path: [pointC2H4, point2C2H4], //路径
            fill: 'balck'
          }, {
            type: 'text',
            x: textLocate[0],
            y: textLocate[1],
            text: i * 20,
            rotate: 60
          })

          var pointC2H2 = this.convertPercentToPoint(0, 100 - 20 * i)
          var point2C2H2 = this.convertPercentToPoint(-dotLinePercent, 100 - 20 * i + dotLinePercent)
          textLocate = this.convertPercentToPoint(-5, 100 - 20 * i + 5)
          triangleitems.push({
            type: 'path',
            path: [pointC2H2, point2C2H2], //路径
            fill: 'balck'
          }, {
            type: 'text',
            x: textLocate[0],
            y: textLocate[1],
            text: i * 20,
          })
        }
      }
      //箭头及描述
      {
        var arrowPath = getArrowPath(this.convertPercentToPoint(40, -25), this.convertPercentToPoint(80, -25))
        var textLocate = this.convertPercentToPoint(60, -30)
        triangleitems.push({
          type: 'path',
          path: arrowPath, //路径
          fill: 'balck'
        }, {
          type: 'text',
          x: textLocate[0],
          y: textLocate[1],
          text: '%CH4',
          rotate: 300
        })

        arrowPath = getArrowPath(this.convertPercentToPoint(115 - 40, 40), this.convertPercentToPoint(115 - 80, 80))
        textLocate = this.convertPercentToPoint(60, 60)
        triangleitems.push({
          type: 'path',
          path: arrowPath, //路径
          fill: 'balck'
        }, {
          type: 'text',
          x: textLocate[0],
          y: textLocate[1],
          text: '%C2H4',
          rotate: 60
        })

        arrowPath = getArrowPath(this.convertPercentToPoint(-10, 115 - 40), this.convertPercentToPoint(-10, 115 - 80))
        textLocate = this.convertPercentToPoint(-20, 120 - 70)
        triangleitems.push({
          type: 'path',
          path: arrowPath, //路径
          fill: 'balck'
        }, {
          type: 'text',
          x: textLocate[0],
          y: textLocate[1],
          text: '%C2H2',
        })
      }

    triangleitems.push({
      type: 'text',
      x: this.option.descStartX + 5,
      y: this.option.descStartY,
      text: '符号说明：',
      font: this.option.descFont,
    })
    for (var i = 0; i < descContent.length; i++) {
      triangleitems.push({
        type: 'rect',
        fill: this.option.colors[i],
        width: this.option.descWidth,
        height: this.option.descHeight,
        x: this.option.descStartX,
        y: this.option.descStartY + this.option.descLineSpace * (i + 1)
      })
      triangleitems.push({
        type: 'text',
        x: this.option.descStartX + this.option.descWidth + this.option.descAdjustX,
        y: this.option.descStartY + this.option.descLineSpace * (i + 1) + this.option.descAdjustY,
        text: descContent[i],
        font: this.option.descFont
      })
    }
    var ctx = canvas.getContext("2d")
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    drawPic(ctx, triangleitems)
  }
}

export default {
  drawDavidTriangle,
  getTriRatioResult
}
