  <div class="modal-header">

  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div class="col-md-6" id="claim-card">
        <div class="form-group">
          <label class="form-label" for="block">Block</label>
          <input type="text" id="blocks" name="blocks" class="form-control" value="{{ $data['block']->name }}">
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="locations">Location</label>
            <select class="form-select" id="locations" name="locations">
            <option value="-" selected disabled>-</option>
            <option value="DESASISWA" {{ ($data['block']->location == 'DESASISWA') ? 'selected' : '' }}>DESASISWA</option>
            <option value="DESASISWI" {{ ($data['block']->location == 'DESASISWI') ? 'selected' : '' }}>DESASISWI</option>
            </select>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
      <div class="form-group pull-right">
          <input type="submit" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit" onclick="add2()">
      </div>
  </div>

<script type="text/javascript">
  function add2()
  {

    var formData = new FormData();

    getInput = {
      block : $('#blocks').val(),
      location : $('#locations').val()
    };

    let id = "{{ $data['block']->id }}";
    
    formData.append('storeBlock', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "/hostel/block/store?idS=" + id,
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.message == "Success"){
                    alert("Success! Block has been updated!");
                    
                    $('#uploadModal').modal('hide');
                    
                    // Start with an empty table structure
                    var newTable = "<table id='table_projectprogress_course' class='table table-striped projects display dataTable no-footer' style='width: 100%;'>" +
                        "<thead class='thead-themed'>" +
                        "<tr>" +
                            "<th>No.</th>" +
                            "<th>Block</th>" +
                            "<th>Location</th>" +
                            "<th></th>" +
                        "</tr>" +
                        "</thead>" +
                        "<tbody>";

                    // Assuming res.data is an array containing block data
                    $.each(res.data, function(i, item) {
                        var newRow = "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + item.name + "</td>" +
                            "<td>" + item.location + "</td>" +
                            "<td class='project-actions text-right' style='text-align: center;'>" +
                              "<a class='btn btn-info btn-sm pr-2' href='#' onclick='getBlock(\"" + item.id + "\")'>" +
                                  "<i class='ti-pencil-alt'></i> Edit" +
                              "</a>" +
                              "<a class='btn btn-danger btn-sm' href='#' onclick='deleteBlock(\"" + item.id + "\")'>" +
                                  "<i class='ti-trash'></i> Delete" +
                              "</a>" +
                            "</td>" +
                        "</tr>";
                        newTable += newRow;
                    });

                    // Close table structure
                    newTable += "</tbody>" +
                                "<tfoot class='tfoot-themed'>" +
                                  "<tr>" +
                                  " " +               
                                  "</tr>" +
                                "</tfoot>" +
                                "</table>";

                    // Replace the div contents with the new table
                    $('#add-student-div').html(newTable);

                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Group code already existed inside the system"){
                        $('#classcode_error').html(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });

  }
</script>