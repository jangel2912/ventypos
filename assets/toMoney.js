let api_auth = JSON.parse(localStorage.getItem('api_auth'));
var datacurrency = {"symbol":"$","decimals":"0","thousands_sep":",","decimals_sep":"."};

function toMoney(x, type){
    const curr = datacurrency
    if(String(x).includes(".")){
        x = String(x).split('.').join('');
    }
    if(String(x).includes(","))
    {
        x = String(x).split(',').join('');
    }
    if(!x){
        x = 0;
    }
    let thousands_sep = ','
    let decimals_sep = '.'
    let decimals = 2
    let value_decimal = ''
    if(curr) {
        thousands_sep = curr.thousands_sep ? curr.thousands_sep: ','
        decimals_sep = curr.decimals_sep ? curr.decimals_sep: ','
        decimals = curr.decimals ? parseFloat(curr.decimals): 2
    }
    if(datacurrency.decimals != '0' && String(x).length > parseFloat(curr.decimals)){
        
        value_decimal =  decimals_sep + x.toString().substr(-decimals);
        x = x.toString().slice(0, -decimals);
    }
    let final_decimal = ''
    for (let i = 0; i < decimals; i++) { final_decimal += '0'}
    const mask = `#${thousands_sep}##0${decimals_sep}${final_decimal}`
    mask
    if( parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep) + value_decimal == NaN || parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep) + value_decimal == "NaN"){
        return 0;
    }
    return parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep) + value_decimal;
}

function number_format (number, decimals = datacurrency.decimals, decPoint = datacurrency.decimals_sep, thousandsSep = datacurrency.thousands_sep) {

    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''
  
    var toFixedFix = function (n, prec) {
      if (('' + n).indexOf('e') === -1) {
        return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
      } else {
        var arr = ('' + n).split('e')
        var sig = ''
        if (+arr[1] + prec > 0) {
          sig = '+'
        }
        return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
      }
    }
  
    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || ''
      s[1] += new Array(prec - s[1].length + 1).join('0')
    }
  
    return s.join(dec)
  }