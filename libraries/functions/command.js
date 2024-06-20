function validate_next_proses(){
   $.messager.confirm('Konfirmasi','Header will be auto save ??',function(r){
    if (r){
      submitForm();
    }
    

  });   
 
}