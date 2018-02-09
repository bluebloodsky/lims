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

export const rollbackArray = (source: Array, logContents: Array) => {
  logContents.map(item => {
    if (item.type == 'add') {
      remove(source, item.newvalue)
    } else if (item.type == 'remove') {
      source.push(item.oldvalue)
    }
  })
}
