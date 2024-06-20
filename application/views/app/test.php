<base href="<?= base_url() ?>">

   <script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>
<div id="capture" style="height: 300px;width: 100%" >
<h1 >Hellooooo   </h1>
<h1 ><b>Hellooooo TEst </b> </h1>
</div>

<img src="" id="imgtest" style="height: 200px;width: 200px">
<button id="btn">Capture</button>

<button onclick="screenShot()" type="button">Take a screenshot</button>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script type="text/javascript">
  function capture() {
    const capture = document.querySelector('#capture')
    html2canvas(capture)
        .then(canvas => {
            document.body.appendChild(canvas)
            canvas.style.display = 'block'
            return canvas
        })
        .then(canvas => {
            // const image = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
                 const image = canvas.toDataURL('image/png');
            uploads(image);
            // console.log(image);
            // const a = document.createElement('a')
            // a.setAttribute('download', 'my-image.png')
            // a.setAttribute('href', image)
            // a.click()
            var myImage = canvas.toDataURL("image/png");
            $("#imgtest").attr("src", image);
            canvas.remove()
        })
}

const btn = document.querySelector('#btn')
btn.addEventListener('click', capture)
</script>

<script type="text/javascript">
  function screenShot(){
    html2canvas(document.querySelector("#capture")).then(canvas => {
        var dataURL = canvas.toDataURL( "image/png" );
        var data = atob( dataURL.substring( "data:image/png;base64,".length ) ),
            asArray = new Uint8Array(data.length);

        for( var i = 0, len = data.length; i < len; ++i ) {
            asArray[i] = data.charCodeAt(i);    
        }

        var blob = new Blob( [ asArray.buffer ], {type: "image/png"} );
        saveAs(blob, "photo.png");
    });
}
</script>

<script type="text/javascript">
  function uploads(image){
    var url = 'web/test_capture_json/uploads';
     var jqxhr = $.post(url,{img:image}, function(data) {
      console.log(data);
     });
  }
</script>
