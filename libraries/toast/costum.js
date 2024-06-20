// const TYPES = ['info', 'warning', 'success', 'error'],
//         TITLES = {
//             'info': 'Notice!',
//             'success': 'Awesome!',
//             'warning': 'Watch Out!',
//             'error': 'Doh!'
//         },
//         CONTENT = {
//             'info': 'Hello, world! This is a toast message.',
//             'success': 'The action has been completed.',
//             'warning': 'It\'s all about to go wrong',
//             'error': 'It all went wrong.'
//         },
//         POSITION = ['top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'];
//         $.toastDefaults.position = 'bottom-center';
//         $.toastDefaults.dismissible = true;
//         $.toastDefaults.stackable = true;
//         $.toastDefaults.pauseDelayOnHover = true;
function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return (date.getMonth()+1) + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
}
  const TYPES = ['info', 'warning', 'success', 'error'],
        TITLES = {
            'info': 'Notice!',
            'success': 'Awesome!',
            'warning': 'Watch Out!',
            'error': 'Doh!'
        },
        CONTENT = {
            'info': 'Hello, world! This is a toast message.',
            'success': 'The action has been completed.',
            'warning': 'It\'s all about to go wrong',
            'error': 'It all went wrong.'
        },
        POSITION = ['top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'];
        $.toastDefaults.position = 'top-right';

        $.toastDefaults.dismissible = true;
        $.toastDefaults.stackable = true;
        $.toastDefaults.pauseDelayOnHover = true;
function show_toast(type,title,content){
    var d = new Date();
    var e = formatDate(d);
     $.toast({
                type: type,
                title: title,
                subtitle: e,
                content: content,
                delay: 5000
            });

}