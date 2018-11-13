/**
 * Created by Bole on 12.11.2018.
 */
let croppie = null;
let el = document.getElementById('resizer');
let banner;

$.base64ImageToBlob = function(str) {
    // extract content type and base64 payload from original string
    let pos = str.indexOf(';base64,');
    let type = str.substring(5, pos);
    let b64 = str.substr(pos + 8);

    // decode base64
    let imageContent = atob(b64);

    // create an ArrayBuffer and a view (as unsigned 8-bit)
    let buffer = new ArrayBuffer(imageContent.length);
    let view = new Uint8Array(buffer);

    // fill the view, using the decoded base64
    for (let n = 0; n < imageContent.length; n++) {
        view[n] = imageContent.charCodeAt(n);
    }

    // convert ArrayBuffer to Blob
    let blob = new Blob([buffer], { type: type });

    return blob;
};

$.getImage = function(input, croppie) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            croppie.bind({
                url: e.target.result
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
};

$("#file-upload").on("change", function(event) {
    $("#myModal").modal();
    // Initailize croppie instance and assign it to global variable
    croppie = new Croppie(el, {
        viewport: {
            width: 200,
            height: 200,
            type: 'square'
        },
        boundary: {
            width: 220,
            height: 220
        },
        enableOrientation: true
    });
    $.getImage(event.target, croppie);
});

$("#upload").on("click", function() {
    croppie.result('base64').then(function (base64) {
        $("#myModal").modal("hide");

        //save croppie
        banner=$.base64ImageToBlob(base64);
    });
});

//when we click save, we send ajax request to the server with all data from form
$('#save').on('click', function(){
    event.preventDefault();
    //delete all errors if any
    $('#name').prev().text('Name').css('color', 'black');
    $('#position').prev().text('Position').css('color', 'black');
    $('#link').prev().text('Link').css('color', 'black');
    $('#file-upload').prev().prev().text('Banner').css('color', 'black');

    //collect values from fields
    let name=$('#name').val().trim();
    let position=$('#position').val().trim();
    let link=$('#link').val().trim();
    if(name=="" || position =="" || link==""){
        //display errors
        if(name==""){
            $('#name').prev().text('Name -> This field can\'t be empty').css('color', 'red');
        }
        if(position==""){
            $('#position').prev().text('Position -> This field can\'t be empty').css('color', 'red');
        }
        if(link==""){
            $('#link').prev().text('Link -> This field can\'t be empty').css('color', 'red');
        }

        return;
    }
    //everything is ok so we can send data via ajax
    let id=$('#id').val();
    let form_data = new FormData();
    if(banner){
        form_data.append('banner', banner);
    }
    form_data.append('id', id);
    form_data.append('name', name);
    form_data.append('link', link);
    form_data.append('position', position);
    let url = "/admin-sponsors-update";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
        }
    });
    $.ajax({
        method: 'POST',
        url: url,
        data: form_data,
        dataType:'json',
        processData: false,
        contentType: false,
        success: function(data) {
            if (data == "success") {
                toastr.success('Sponsor updated successfully!');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                toastr.error('There was some error on the server');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});


// To Rotate Image Left or Right
$(".rotate").on("click", function() {
    croppie.rotate(parseInt($(this).data('deg')));
});

$('#myModal').on('hidden.bs.modal', function (e) {
    // This function will call immediately after model close
    // To ensure that old croppie instance is destroyed on every model close
    setTimeout(function() { croppie.destroy(); }, 100);
});
