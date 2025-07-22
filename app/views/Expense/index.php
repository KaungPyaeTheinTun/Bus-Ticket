<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="wrapper d-flex align-items-stretch">
    <?php require_once APPROOT . '/views/inc/sidebar.php'; ?>
    <div id="content" class="p-4 p-md-5">
        <?php require_once APPROOT . '/views/inc/navbar.php'; ?>
        <div class="container">
        <h1 class="ml-0 my-5">Expense</h1>
		<?php require APPROOT . '/views/components/auth_message.php'; ?>
		
        <!-- <div class="row">
            <div class="input-group mb-3 col-lg-6">
                <label for="">Show
                    <select class="custom-select" id="inputGroupSelect02" style="width: 35%;">
                      <option selected>Choose...</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                    Entries
                </label>
            </div>
              <label for="" class="d-flex" style="margin-left: 100px;">
                  Search: <input class="form-control form-control-sm ml-2 " type="text">
              </label>
        </div> -->
            
            <table id="expenseTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Category</th>
						<th>Amount</th>
                        <th>Quantity</th>
                        <th>Assigned By</th>
                        <th>Date</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

            </table>
        </div>    
        <button type="submit" class="btn btn-warning float-right px-5"><a href="<?php echo URLROOT; ?>/expense/create">Add New</a></button>
    </div>

	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
					<div class="modal-header">						
						<h4 class="modal-title">Edit Categories</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Type</label>
							<input type="email" class="form-control" required>
						</div>	
						<div class="form-group">
							<label>description</label>
							<textarea class="form-control" required></textarea>
						</div>			
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-info" value="Save">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="<?php echo URLROOT; ?>/expense/destroy/<?php echo base64_encode($expense['id']); ?>" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Delete Employee</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-danger" value="Delete">
					</div>
				</form>
			</div>
		</div>
	</div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script type="text/javascript">
	$(document).ready(function () {
		$('#expenseTable').DataTable({
			"ajax" : "<?php echo URLROOT; ?>/expense/expenseData",
			"columns" : [
				{ "data" : "id" },
				{ "data" : "category_name" },
				{ "data" : "amount" },
				{ "data" : "qty" },
				{ "data" : "user_name" },
				{ "data" : "date" },
				{
					mRender : function (data, type, full) {
						// console.log(full);
						return '<a href="<?php echo URLROOT; ?>/expense/edit/' + full.id + '" type="submit" class="btn btn-primary">Edit</a>'
					}
				},
				{
					mRender : function (data, type, full) {
						// console.log(full);
						return '<button type="submit" value="' + full.id + '" class="btn btn-danger delete">Delete</button>'
					}
				}
			]
		});

		$(document).on('click', '.delete', function () {
			
			var url_id = $(this).val();
			// alert(url_id);

			var form_url = '<?php echo URLROOT; ?>/expense/destroy/' + url_id;
			// alert(form_url);

			swal({
    	    title: "Are you sure?",
    	    text: "You will not be able to recover this imaginary file!",
    	    type: "warning",
    	    showCancelButton: true,
    	    confirmButtonClass: "btn-danger",
    	    confirmButtonText: "Yes, Delete",
    	    cancelButtonText: "Cancel",
    	    closeOnConfirm: false,
    	    closeOnCancel: false
    	  },
    	  function(isConfirm) {
    	    if (isConfirm) {
    	      $.ajax({
    	         url: form_url,
    	         type: 'DELETE',
    	         error: function() {
    	            alert('Something is wrong');
    	         },
    	         success: function(data) {
    	              $("#"+url_id).remove();
    	            //   swal("Deleted!", "Your imaginary file has been deleted.", "success");
					  window.location.reload();
    	         }
    	      });
    	    } else {
    	      swal("Cancelled", "Your imaginary file is safe :)", "error");
    	    }
    	  });

			// $.ajax({
			// 	url : form_url,
			// 	type : 'POST',
			// 	data : {
			// 		id : url_id
			// 	},
			// 	success: function () {
			// 		window.location.reload();
            //     }
			// });
			
		});
	});
</script>
