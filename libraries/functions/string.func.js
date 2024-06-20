// function onlyNumber(this){
//  var values;
// // values = this.value.replace('/[^0-9.]/g', '').replace('/(\..*)\./g', '$1');
// return values;
// }
//onkeypress='validate(event)'
function validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}


 // onchange="numberWithCommas('field_id_name')"
 // onkeyup="numberWithCommas('field_id_name')" 
function numberWithCommas(x) {
  var nilai= $("#"+x).val();
   // CekNumber(x); // console.log(nilai);
   var nominal=formatRupiah(nilai);
   $("#"+x).val(nominal)
   // console.log(formatRupiah(nilai));
       // var hasil_nilai= nilai.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
      // $("#"+x).val(hasil_nilai);

    }
    function formatRupiah(angka, prefix){
      var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split       = number_string.split(','),
      sisa        = split[0].length % 3,
      rupiah        = split[0].substr(0, sisa),
      ribuan        = split[0].substr(sisa).match(/\d{3}/gi);
      
  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}


function kekata_indo(x) 
{
  var x = parseInt(x);
  var angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
  var temp = "";
  if (x <12) 
  {
    temp = " "+ angka[x];
  } 
  else if (x <20) 
  {
    temp = kekata_indo(x - 10)+ " Belas";
  } 
  else if (x <100) 
  {
    temp = kekata_indo(x/10)+" Puluh"+ kekata_indo(x % 10);
  } 
  else if (x <200) 
  {
    temp = " Seratus" + kekata_indo(x - 100);
  } 
  else if (x <1000) 
  {
    temp = kekata_indo(x/100) + " Ratus" + kekata_indo(x % 100);
  } 
  else if (x <2000) 
  {
    temp = " Seribu" + kekata_indo(x - 1000);
  } 
  else if (x <1000000) 
  {
    temp = kekata_indo( x/1000) + " Ribu" + kekata_indo(x % 1000);
  } 
  else if (x <1000000000) 
  {
    temp = kekata_indo(x/1000000) + " Juta" + kekata_indo(x % 1000000);
  } 
  else if (x <1000000000000) 
  {
    temp = kekata_indo(x/1000000000) + " Milyar" + kekata_indo(x %1000000000);
  } 
  else if (x <1000000000000000) 
  {
    temp = kekata($x/1000000000000) + " Trilyun" + kekata(x % 1000000000000);
  }      
  
  return temp ;
}

function kekata_english(x) 
{
  var x = parseInt(x);
  var angka = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven"];
  var temp = "";
  if (x <12) 
  {
    temp = " "+ angka[x];
  } 
  else if (x <20) 
  {
    temp = kekata_english(x - 10)+ " Teens";
  } 
  else if (x <100) 
  {
    //temp = kekata_english(x/10)+" Twenty"+ kekata_english(x % 10);
    if(x < 30)
    {
      temp = " Twenty"+ kekata_english(x % 10);
    }
    else if(x < 40)
    {
      temp = " Thirty"+ kekata_english(x % 10);
    }
    else if(x < 50)
    {
      temp = " Fourty"+ kekata_english(x % 10);
    }
    else if(x < 60)
    {
      temp = " Fifty"+ kekata_english(x % 10);
    }
    else if(x < 70)
    {
      temp = " Sixty"+ kekata_english(x % 10);
    }
    else if(x < 80)
    {
      temp = " Seventy"+ kekata_english(x % 10);
    }
    else if(x < 90)
    {
      temp = " Eighty"+ kekata_english(x % 10);
    }
    else if(x < 100)
    {
      temp = " Ninety"+ kekata_english(x % 10);
    }
  } 
  else if (x <200) 
  {
    temp = kekata_english(x/100) + " hundred" + kekata_english(x - 100);
  } 
  else if (x <1000) 
  {
    temp = kekata_english(x/100) + " Hundred " + kekata_english(x % 100);
  } 
  else if (x <2000) 
  {
    temp = " thousand " + kekata_english(x - 1000);
  } 
  else if (x <1000000) 
  {
    temp = kekata_english( x/1000) + " Thousand" + kekata_english(x % 1000);
  } 
  else if (x <1000000000) 
  {
    temp = kekata_english(x/1000000) + " Million" + kekata_english(x % 1000000);
  } 
  else if (x <1000000000000) 
  {
    temp = kekata_english(x/1000000000) + " Billion" + kekata_english(x %1000000000);
  } 
  else if (x <1000000000000000) 
  {
    temp = kekata_english($x/1000000000000) + " Trillion" + kekata_english(x % 1000000000000);
  }      
  
  return temp  ;
}

// function kekata_english(number) {
//     number = Number(number);
//     var hyphen      = '-';
//     var conjunction = ' and ';
//     var separator   = ', ';
//     var negative    = 'negative ';
//     var decimal     = ' point ';
//     var dictionary = {
//       "0": "zero",
//       "1": "one",
//       "2": "two",
//       "3": "three",
//       "4": "four",
//       "5": "five",
//       "6": "six",
//       "7": "seven",
//       "8": "eight",
//       "9": "nine",
//       "10": "ten",
//       "11": "eleven",
//       "12": "twelve",
//       "13": "thirteen",
//       "14": "fourteen",
//       "15": "fifteen",
//       "16": "sixteen",
//       "17": "seventeen",
//       "18": "eighteen",
//       "19": "nineteen",
//       "20": "twenty",
//       "30": "thirty",
//       "40": "fourty",
//       "50": "fifty",
//       "60": "sixty",
//       "70": "seventy",
//       "80": "eighty",
//       "90": "ninety",
//       "100": "hundred",
//       "1000": "thousand",
//       "1000000": "million",
//       "1000000000": "billion",
//       "1000000000000": "trillion",
//       "1000000000000000": "quadrillion",
//       "1000000000000000000": "quintillion"
//     };

//     if (isNaN(number)) {
//         return "";
//     }

//     if (number < 0) {
//         return negative + kekata_english(abs(number));
//     }

//     var string, fraction =;
//     if(number.toString().includes(".")){
//         var strNumber = number.toString().split(".");
//         number = Number(strNumber[0]);
//         fraction = Number(strNumber[1]);
//     }

//     switch (true) {
//         case number < 21:
//             string = dictionary[number];
//             break;
//         case number < 100:
//             tens   = (parseInt(number / 10)) * 10;
//             units  = number % 10;
//             string = dictionary[tens];
//             if (units) {
//                 string += hyphen + dictionary[units];
//             }
//             break;
//         case number < 1000:
//             hundreds  = number / 100;
//             remainder = number % 100;
//             string    = dictionary[hundreds] + ' ' + dictionary[100];
//             if (remainder) {
//                 string += conjunction + kekata_english(remainder);
//             }
//             break;
//         default:
//             baseUnit     = Math.pow(1000, Math.floor(Math.log(number, 1000)));
//             numBaseUnits = parseInt(number / baseUnit);
//             remainder    = number % baseUnit;
//             string       = kekata_english(numBaseUnits) + ' ' + dictionary[baseUnit];
//             if (remainder) {
//                 string += remainder < 100 ? conjunction : separator;
//                 string += kekata_english(remainder);
//             }
//             break;
//     }

//     if (null !== fraction && !isNaN(fraction)) {
//         string += decimal;
//         words = array();
//         foreach (str_split((string) fraction) as number) {
//             words[] = dictionary[number];
//         }
//         string += implode(' ', words);
//     }

//     return ucwords(string);
// }



function hitungSelisihHari(tgl1, tgl2){
    // varibel miliday sebagai pembagi untuk menghasilkan hari
    var miliday = 24 * 60 * 60 * 1000;
    //buat object Date
    var tanggal1 = new Date(tgl1);
    var tanggal2 = new Date(tgl2);
    // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    var tglPertama = Date.parse(tanggal1);
    var tglKedua = Date.parse(tanggal2);
    var selisih = (tglKedua - tglPertama) / miliday;
    return selisih;
    }

function datenow(){
  var today = new Date();
       var dd = String(today.getDate()).padStart(2, '0');
         var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
         var yyyy = today.getFullYear();

       var  tgl2 = mm + '/' + dd + '/' + yyyy;
         
        return tgl2;
}
