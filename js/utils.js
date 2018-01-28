function listObj(obj){
  document.write('<ul>');
  for (el in obj){
    document.write('<li>'+el+' - '+obj[el]);
    if (typeof obj[el]=='object'){
      listObj(obj[el]);
    }
    document.write('</li>');
  }
  document.write('</ul>');
}
