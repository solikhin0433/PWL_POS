<form action="{{ url('/penjualan/import_ajax') }}" method="POST" id="form-import-penjualan" enctype="multipart/form-data">
     @csrf
     <div id="modal-master-penjualan" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Import Data Penjualan</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="form-group">
                     <label>Download Template</label>
                     <a href="{{ asset('template_penjualan.xlsx') }}" class="btn btn-info btn-sm" download>
                         <i class="fa fa-file-excel"></i> Download
                     </a>
                     <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                 </div>
                 <div class="form-group">
                     <label>Pilih File</label>
                     <input type="file" name="file_penjualan" id="file_penjualan" class="form-control" required>
                     <small id="error-file_penjualan" class="error-text form-text text-danger"></small>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                 <button type="submit" class="btn btn-primary">Upload</button>
             </div>
         </div>
     </div>
 </form>
 
 <script>
     $(document).ready(function() {
         $("#form-import-penjualan").validate({
             rules: {
                 file_penjualan: {
                     required: true,
                     extension: "xlsx"
                 },
             },
             submitHandler: function(form) {
                 var formData = new FormData(form);  // Convert form to FormData to handle file uploads
 
                 $.ajax({
                     url: form.action,
                     type: form.method,
                     data: formData,     // Send the FormData (including the file)
                     processData: false, // Prevent jQuery from processing the data
                     contentType: false, // Prevent jQuery from overriding content type
                     success: function(response) {
                         if (response.status) { // On success
                             $('#modal-master-penjualan').modal('hide');
                             Swal.fire({
                                 icon: 'success',
                                 title: 'Berhasil',
                                 text: response.message
                             });
                             dataPenjualan.ajax.reload(); // Reload the DataTable
                         } else { // On error
                             $('.error-text').text('');
                             $.each(response.msgField, function(prefix, val) {
                                 $('#error-' + prefix).text(val[0]);
                             });
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Terjadi Kesalahan',
                                 text: response.message
                             });
                         }
                     },
                     error: function(xhr, status, error) {
                         Swal.fire({
                             icon: 'error',
                             title: 'Error',
                             text: 'Terjadi kesalahan pada server'
                         });
                     }
                 });
                 return false; // Prevent default form submission
             },
             errorElement: 'span',
             errorPlacement: function(error, element) {
                 error.addClass('invalid-feedback');
                 element.closest('.form-group').append(error);
             },
             highlight: function(element, errorClass, validClass) {
                 $(element).addClass('is-invalid');
             },
             unhighlight: function(element, errorClass, validClass) {
                 $(element).removeClass('is-invalid');
             }
         });
     });
 </script>
 