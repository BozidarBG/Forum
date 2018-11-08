deo za ostale modal funkcije
<script>
    //kada se šalje forma modala
    $('#student_form').on('submit', function(event){
        event.preventDefault();
        //podatke konvertuje u string i stavlja u data var
        var form_data=$(this).serialize();

        //šaljemo ajax request
        //dataType znači u kom formatu želimo da dobijemo podatke sa servera
        $.ajax({
            url:'{{route("ajaxdata.postdata")}}',
            method: 'POST',
            data: form_data,
            dataType:'json',
            success: function(data){
                //da li imamo greške
                //console.log(data)
                if(data.error.length>0){
                    //imamo greške, treba da ih prikažemo
                    var error_html='';
                    for(var i=0; i<data.error.length; i++){
                        error_html +='<div class="alert alert-danger">'+data.error[i]+'</div>';
                    }
                    console.log(error_html)
                    //stavljamo greške u modal
                    $('#form_output').html(error_html);
                }else{
                    //reloadujemo datatable sa novim podacima koje smo snimili
                    $('#student_table').DataTable().ajax.reload();
                    //nema grešaka i hoćemo success poruku ispod modala da prikažemo
                    $('#form_output').html(data.success);
                    //resetujemo vrednosti svih polja u formi
                    $('#student_form')[0].reset();
                    //vraćamo vrednost submita da bude Add
                    $('#action').val('Add');
                    //resetujemo title modala
                    $('.modal-title').text('Add Data');
                    //setujemo vrednost input hidden polja na insert
                    $('#button_action').val('insert');

                }
                //trebalo bi page reload posle ovoga jer ostaje staro ime nakon što se ugasi modal ili da sami promenimo
                //location.reload();
            }
        });
    });//kraj slanja forme

    //edit btns. uzimamo id iz tabele, šaljemo ajax req, dobijamo podatke, otvaramo modal, u modal upisujemo podatke
    //menjamo dugmiće u Edit, input hidden button_action u update i kad se klikne, šalje se na server apdejtovan student
    $(document).on('click', '.edit', function(){
        var id=$(this).attr('id');
        //pravimo ajax zahtev da uzmemo podatke o studentu sa ovim aj dijem
        $.ajax({
            url: "{{route('ajaxdata.fetchdata')}}",
            method: 'get',
            data: {id:id},
            dataType: 'json',
            success:function(data){
                //u modal ubacujemo podatke koje smo dobili za editovanje
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                //u hidden polje futera, ubacujemo id
                $('#student_id').val(id);
                $('#studentModal').modal('show');
                //menjamo dugme submit u Edit i naslov modala u Edit data
                $('#action').val('Edit');
                $('.modal-title').text('Edit Data');
                //menjamo vrednost hidden polja iz insert u update, da bi kontroler znao da li da update ili insert
                $('#button_action').val('update');
            }
        });
    });//end edit

    //delete request
    $(document).on('click', '.delete', function(){

        //pravimo ajax zahtev da brišemo studenta
        if(confirm('Are you sure that you want to delete this student?')){
            var id=$(this).attr('id');
            $.ajax({
                url: "{{route('ajaxdata.removedata')}}",
                method: 'get',
                data: {id:id},
                dataType: 'json',
                success:function(data){
                    alert(data);
                    $('#student_table').DataTable().ajax.reload();
                }
            });
        }else{
            return false;
        }

    });//end delete

    //bulk delete
    //kliknemo dugme, obriše sve čekirane redove
    $(document).on('click','#bulk_delete', function(){
        var id=[]
        if(confirm('Are you sure that you want to delete these data?')){
            $('.student_checkbox:checked').each(function(){
                //svaki checkbox ima value=$id. ovim stavljamo $id u niz id
                id.push($(this).val());
                if(id.length>0){
                    //šaljemo ajax zahtev za brisanje
                    $.ajax({
                        url: "{{route('ajaxdata.massremove')}}",
                        data: {id:id},
                        method: "get",
                        success:function(data){
                            alert(data);
                            $('#student_table').DataTable().ajax.reload();
                        }

                    });

                }else{
                    //ništa nije čekirano
                    alert('Please select at least one row to be deleted');
                }
            })
        }
    })

</script>

<!--student modal start-->
<div class="modal fade" id="studentModal" role="dialog">
    <div class="modal-dialog"><!--daje width i margin-->
        <div class="modal-content"><!--daje border i background color-->
            <form method="post" id="student_form">
                <div class="modal-header"><!--daje style za modal header-->
                    <h4 class="modal-title">Add Data</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button><!-- x dugme za zatvaranje-->

                </div>
                <div class="modal-body"><!--daje style za modal body-->
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Enter first name</label>
                        <input type="text" name="first_name" class="form-control" id="first_name">
                    </div>
                    <div class="form-group">
                        <label>Enter last name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id" value="">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- student modal end -->

<!--login modal start-->
<div class="modal fade" id="studentModal" role="dialog">
    <div class="modal-dialog"><!--daje width i margin-->
        <div class="modal-content"><!--daje border i background color-->
            <form method="post" id="student_form">
                <div class="modal-header"><!--daje style za modal header-->
                    <h4 class="modal-title">Add Data</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button><!-- x dugme za zatvaranje-->

                </div>
                <div class="modal-body"><!--daje style za modal body-->
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Enter first name</label>
                        <input type="text" name="first_name" class="form-control" id="first_name">
                    </div>
                    <div class="form-group">
                        <label>Enter last name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id" value="">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- login modal end -->

error: function(data){
console.log('eror imamo', data)
},
success: function(data){
//da li imamo greške
console.log('tekst', data.statusText)
if(data.statusText=="OK"){
console.log('jeste')
$('#loginModal').modal('hide');
location.reload();
}

}


<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestionTag extends Migration
{
/**
 * Run the migrations.
 *
 * @return void
*/
public function up()
{
Schema::create('question_tag', function (Blueprint $table) {
$table->integer('question_id');
$table->integer('tag_id');
$table->foreign('question_id')->references('id')->on('questions');
$table->foreign('tag_id')->references('id')->on('tags');
});


}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
Schema::dropIfExists('question_tag');
}
}



<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
/**
* Run the migrations.
*
* @return void
*/
public function up()
{
Schema::create('tags', function (Blueprint $table) {
$table->increments('id');
$table->string('title');
$table->string('slug');
$table->timestamps();
});
}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
Schema::dropIfExists('tags');
}
}
