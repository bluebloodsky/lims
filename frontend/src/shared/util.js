/* @flow */

export const copyObject = (source: Object | Array < * > ) => {
  return JSON.parse(JSON.stringify(source))
}

/**
 * Remove an item from an array
 */
export const remove = (arr: Array < any > , item: any) => {
  if (arr.length) {
    const index = arr.findIndex(it => {
      return JSON.stringify(it) == JSON.stringify(item)
    })
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

export const rollbackMap = (source: Object | Array, logContents: Object| Array) => {
  for (let [k, v] of Object.entries(logContents)) {
    if (v.type == 'add') {
      if (Array.isArray(source)) {
        source.splice(k, 1)
      } else {
        delete source[k]
      }
    } else if (v.type == 'remove' || v.type == 'change') {
      source[k] = v.oldvalue
    } else {
      rollbackMap(source[k], logContents[k])
    }
  }
}

export const rollbackList = (source: Array , logContents:Array) =>{
  logContents.map(log =>{
    if(log.type == 'add'){
      remove(source , log.newvalue)
    }else if(log.type == 'remove'){
      source.push(log.oldvalue)
    }
  })  
}

export const mixObject = (_to:Object,_from:Object) =>{
  for(const key in _from){
    if(!_to[key]){
      _to[key] = copyObject(_from[key])
    }else if(typeof _from[key] === 'object'){
      mixObject(_to[key] , _from[key])
    }
  }
  return _to
}