$(document).ready(function(){
    let token=$('input[name=_token]').val();
    let data={};
    data._token=token;

//if we click ok, modal should appear to confirm if we want to ban od un-ban this user
    $('.confirm-modal').on('click', function (){
        let time=$(event.target).attr('data-time');
        let text;

        let name=$(event.target).parent().attr('data-name');
        let id=$(event.target).parent().attr('data-id');
        if(time==10){
            text="Are you sure that you want to ban "+name+" forever?";
        }else if(time==0){
            text="Are you sure that you want to  remove ban for "+name+"?";
        }else{
            text="Are you sure that you want to ban "+name+" for "+time+" days?";
        }
        $('#confirmModal').modal('show');
        $('#confirmModal .modal-header span').text(text);
        //if we click send, we will take id of that user and ban time and send it on server
        data.time=time;
        data.id=id;
        $('#send').on('click', function(){
            $.ajax({
                url:"/admin-ban",
                method:'post',
                data:data,
                dataType:'json',
                success:function(data){
                    //console.log(data)
                    if(data.success){
                        toastr.success(data.success);
                        $('#confirmModal').modal('hide');
                        setTimeout(function(){
                            location.reload();
                        }, 1500);


                    }else{
                        toastr.error(data.error);
                        $('#confirmModal').modal('hide');
                    }
                }
            });

        });

    });



});


