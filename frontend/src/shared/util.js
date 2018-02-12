/* @flow */

export const copyObject = (source:  Object | Array<*>) => {
  return JSON.parse(JSON.stringify(source))
}

/**
 * Remove an item from an array
 */
export const remove = (arr: Array < any > , item: any) => {
  if (arr.length) {
    const index = arr.findIndex(it=>{
    	return JSON.stringify(it) == JSON.stringify(item)
    })
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

export const rollbackMap = (source: Array|Object, logContents: Array) => {
  logContents.map((item,key) => {
    if (item.type == 'add') {
      source.splice(key,1)
    } else{
      source[key] = item.oldvalue
    }
  })
}
