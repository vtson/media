function readURL(input) {
    if (input.files && input.files[0]) {
        var fileType = input.files[0].type.split('/');
        var reader = new FileReader(),
        image = $('.reviewImageUpload'),
        video = $('.reviewVideoUpload');
        reader.onload = function (e) {

            if (fileType[0] === 'image') {
                image.removeAttr('hidden');
                image.attr('src', e.target.result);
                video.attr('hidden',true);
                video.attr('src', '#');
            }
            if (fileType[0] === 'video') {
                video.removeAttr('hidden');
                video.attr('src', e.target.result);
                image.attr('hidden',true);
                image.attr('src', '#');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change','.file-input', function () {
    readURL(this);
});
