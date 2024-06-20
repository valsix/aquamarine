<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Responsive, Fixed and Resizing Header</title>
<script src="jQuery.js"></script>
<script type="text/javascript">
$(function() {
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 200) {
            $(".header").addClass('smaller');
        } else {
            $(".header").removeClass("smaller");
        }
    });
});
</script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div id="warp">
    <div class="header">
        <div class="container clearfix">
            <h1 id="logo"> LOGO </h1>
            <nav>
                <a href="">Lorem</a>
                <a href="">Ipsum</a>
                <a href="">Dolor</a>
            </nav>
        </div>
    </div><!-- /header -->
    <div id="content-warp">
    	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et est tincidunt, lacinia metus consectetur, tristique nibh. Vivamus vitae hendrerit arcu. Integer gravida lacus ut mi fringilla, non condimentum elit ullamcorper. Nunc id egestas nibh. Nulla in libero eleifend, malesuada dolor a, rutrum nisi. Mauris fringilla elit massa, a sodales leo consectetur eget. Quisque id sem adipiscing, sodales lacus id, venenatis nisi. Fusce scelerisque scelerisque arcu nec auctor. Curabitur id leo metus. Phasellus ut vehicula ipsum. Nulla consequat porttitor erat, sit amet mattis libero.</p>
    	<p>Fusce viverra sapien in magna molestie porttitor. Donec in diam at risus congue adipiscing vel nec nunc. Morbi blandit erat convallis, convallis lacus quis, tincidunt mi. Duis vitae mattis lectus. Maecenas pellentesque nisl a cursus posuere. Donec blandit ipsum sagittis, sagittis lectus non, facilisis ante. Nunc pulvinar turpis sodales velit sodales, vitae venenatis augue consequat. In porttitor placerat magna sed tempor. Donec vitae dictum libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet nisl ut erat pellentesque tincidunt. Donec ut mi eget neque aliquet venenatis. Etiam sit amet nulla viverra, pharetra lectus quis, mattis arcu. Fusce in ante justo. Nunc vehicula vitae elit sed fringilla. Nulla et lectus vitae elit dapibus molestie eget sit amet libero.</p>
    	<p>Morbi elit diam, sollicitudin non neque eget, accumsan commodo ipsum. Ut venenatis nunc non ligula eleifend egestas. Suspendisse eu mi quis ante porttitor faucibus. Proin neque augue, pretium vitae iaculis eu, fermentum eu eros. Cras vitae quam bibendum, gravida augue eget, tincidunt libero. Integer tincidunt dolor enim, quis mollis neque rutrum eu. Donec id sagittis enim. Curabitur eget rutrum est, ac bibendum urna. Nulla vitae mattis neque. In et mauris egestas, gravida ligula et, egestas enim. Sed ante erat, pellentesque quis bibendum eu, porttitor nec erat.</p>
    	<p>Nullam eget magna eget leo fringilla ullamcorper. Quisque interdum eu risus ut iaculis. Sed varius imperdiet sapien ac rutrum. Ut ut gravida dolor. Duis nec felis porttitor, mollis sapien vitae, interdum velit. Sed semper blandit enim, vitae feugiat eros molestie at. In facilisis urna libero, et pretium diam auctor auctor. Duis vulputate facilisis ipsum in aliquet. Integer porta sapien sapien. Donec nulla mauris, vehicula ut nunc ac, porta condimentum augue. Curabitur eget turpis lorem.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et est tincidunt, lacinia metus consectetur, tristique nibh. Vivamus vitae hendrerit arcu. Integer gravida lacus ut mi fringilla, non condimentum elit ullamcorper. Nunc id egestas nibh. Nulla in libero eleifend, malesuada dolor a, rutrum nisi. Mauris fringilla elit massa, a sodales leo consectetur eget. Quisque id sem adipiscing, sodales lacus id, venenatis nisi. Fusce scelerisque scelerisque arcu nec auctor. Curabitur id leo metus. Phasellus ut vehicula ipsum. Nulla consequat porttitor erat, sit amet mattis libero.</p>
    	<p>Fusce viverra sapien in magna molestie porttitor. Donec in diam at risus congue adipiscing vel nec nunc. Morbi blandit erat convallis, convallis lacus quis, tincidunt mi. Duis vitae mattis lectus. Maecenas pellentesque nisl a cursus posuere. Donec blandit ipsum sagittis, sagittis lectus non, facilisis ante. Nunc pulvinar turpis sodales velit sodales, vitae venenatis augue consequat. In porttitor placerat magna sed tempor. Donec vitae dictum libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet nisl ut erat pellentesque tincidunt. Donec ut mi eget neque aliquet venenatis. Etiam sit amet nulla viverra, pharetra lectus quis, mattis arcu. Fusce in ante justo. Nunc vehicula vitae elit sed fringilla. Nulla et lectus vitae elit dapibus molestie eget sit amet libero.</p>
    	<p>Morbi elit diam, sollicitudin non neque eget, accumsan commodo ipsum. Ut venenatis nunc non ligula eleifend egestas. Suspendisse eu mi quis ante porttitor faucibus. Proin neque augue, pretium vitae iaculis eu, fermentum eu eros. Cras vitae quam bibendum, gravida augue eget, tincidunt libero. Integer tincidunt dolor enim, quis mollis neque rutrum eu. Donec id sagittis enim. Curabitur eget rutrum est, ac bibendum urna. Nulla vitae mattis neque. In et mauris egestas, gravida ligula et, egestas enim. Sed ante erat, pellentesque quis bibendum eu, porttitor nec erat.</p>
    	<p>Nullam eget magna eget leo fringilla ullamcorper. Quisque interdum eu risus ut iaculis. Sed varius imperdiet sapien ac rutrum. Ut ut gravida dolor. Duis nec felis porttitor, mollis sapien vitae, interdum velit. Sed semper blandit enim, vitae feugiat eros molestie at. In facilisis urna libero, et pretium diam auctor auctor. Duis vulputate facilisis ipsum in aliquet. Integer porta sapien sapien. Donec nulla mauris, vehicula ut nunc ac, porta condimentum augue. Curabitur eget turpis lorem.</p>
    
    </div>
</div>

</body>
</html>