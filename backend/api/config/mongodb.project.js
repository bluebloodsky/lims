db = connect("localhost:27017/lims")
/*试验室*/
db.projects.drop()
db.projects.insertMany([
{
	"order_info": {
		"order_client": {
			"name": "1",
			"address": "2",
			"email": "1",
			"linkman": "2",
			"tel": "1",
			"postcode": "2"
		},
		"order_server": {
			"name": "1",
			"address": "2",
			"email": "1",
			"linkman": "2",
			"tel": "1",
			"open_unit": "2",
			"postcode": "1",
			"bank_account": "2",
			"open_bank": "1",
			"tax_num": "2"
		},
		"order_content": {
			"report_type": "试验报告",
			"report_unit": "电力工业电气设备质量检验测试中心",
			"test_type": ["型式试验", "例行检验", "性能试验(多项)"],
			"stamp_type": ["CNAS L0699", "CMA 中国电力科学研究院", "CMA 电力工业电气设备质量检验测试中心", "检测报告专用章"],
			"report_format": "中文版",
			"report_num": "2",
			"test_money": "1"
		}
	},
	"test_items":{
		"fhwtwgjc":{
			"params":{
				"fhwtwgjc":{}
			},
			"records":{
			}
		},
		"cysy":{
			"params":{
				"ldcjcysy":{
					"ldcjcy" : "12"
				},
				"czcjcysy":{
					"czcjcy" : "15"
				}
			}
		},
		"jxfhsy":{
			"params":{
				"wq":{
					"bend_select":"F1",
					"bend_f1":"12",
				}
			}
		}
	}
}
])