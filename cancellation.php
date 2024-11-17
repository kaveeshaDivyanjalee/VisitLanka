<?php
include("header.php");
$totalamt = 0;
?>
<!-- Sub Banner Start -->
<div class="mg_sub_banner">
	<div class="container">
		<h2>Cancellation Panel</h2>
	</div>
</div>
<!-- Sub Banner Start -->
<!--start main -->
<div class="iqoniq_contant_wrapper">
	<section class="gray-bg aboutus-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="about-us">
						<div class="text">
							<center><b style="font-size:25px;color:red;">Room Booking records</b></center>
							<table class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Bill No.</th>
										<th>Hotel</th>
										<th>Room Type</th>
										<?php
										if (!isset($_SESSION['customer_id'])) {
										?>
											<th>Customer</th>
										<?php
										}
										?>
										<th>Booked for</th>
										<th>Booking date</th>
										<th>Cost</th>
										<th>Cancellation charge</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT * FROM room_booking LEFT JOIN hotel ON room_booking.hotel_id=hotel.hotel_id LEFT JOIN room_type ON room_booking.room_typeid=room_type.room_typeid LEFT JOIN customer ON room_booking.customer_id=customer.customer_id LEFT JOIN payment ON payment.room_booking_id=room_booking.room_booking_id WHERE room_booking.status='Active'  AND payment.food_order_id='0' AND payment.cab_bookingid='0' AND payment.payment_id='$_GET[paymentid]' ";
									if (isset($_SESSION['customer_id'])) {
										$sql = $sql . " AND room_booking.customer_id='$_SESSION[customer_id]'";
									}
									$sql = $sql . " ORDER BY room_booking.room_booking_id desc";
									$qsql = mysqli_query($con, $sql);
									while ($rs = mysqli_fetch_array($qsql)) {
										$room_booking_id = $rs['room_booking_id'];
										$sqlhotel_image = "SELECT * FROM hotel_image WHERE hotel_id='$rs[1]' AND room_typeid='0'";
										$qsqlhotel_image = mysqli_query($con, $sqlhotel_image);
										$rshotel_image = mysqli_fetch_array($qsqlhotel_image);

										$checkin = date("d-M-Y", strtotime($rs['check_in']));
										$checkout = date("d-M-Y", strtotime($rs['check_out']));
										echo "<tr>
			<th>
			<center>
			<p style='color:red;font-size:25px;'>
			$rs[payment_id]</p>
			</center>
			</th>
			<td>$rs[hotel_image] $rs[hotel_name]</td>
			<td>$rs[room_type]<br>(Rs.$rs[cost] / day)</td>";
										if (!isset($_SESSION['customer_id'])) {
											echo "<td>$rs[customer_name]</td>";
										}
										echo "<td>Adults : $rs[no_ofadults]<br>Children: $rs[no_ofchildren]</td>
			<td>
			CheckIn : $checkin<br>Checkout : $checkout <br><b style='color:green;'>";
										$checkin = strtotime($rs['check_in']);
										$checkout = strtotime($rs['check_out']);
										$datediff = $checkout - $checkin;
										$nodays = round($datediff / (60 * 60 * 24));
										$nodays = $nodays + 1;
										if ($nodays == 1) {
											echo $nodays . " Day";
										} else {
											echo $nodays . " Days";
										}
										echo "</b></td>
			<td>Rs." . $rs['cost'] * $nodays . "</td>
			<td>Rs.";
										echo $rs['cost'] * $nodays / 2;
										echo "</td></tr>";
										$totalamt = $totalamt + ($rs['cost'] * $nodays);
									}
									?>
								</tbody>
							</table>
							<hr>




							<input type="hidden" name="room_booking_id" id="room_booking_id" value="<?php echo $room_booking_id; ?>">
							<input type="hidden" name="totalamt" id="totalamt" value="<?php echo $totalamt / 2; ?>">
							<center><b style="font-size:25px;color:red;">Cancellation details</b></center>
							<table class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Total Amount</th>
										<td>Rs. <?php echo $totalamt; ?></td>
									</tr>
									<tr>
										<th>Total cancellation charge(50%)</th>
										<td>Rs. <?php echo $totalamt / 2; ?></td>
									</tr>
									<tr>
										<th>Refundable amount</th>
										<td>Rs. <?php echo $totalamt / 2; ?></td>
									</tr>
									<tr>
										<th colspan="2" style='color:red;'>Enter the bank account details to refund... </th>
									</tr>

									<tr>
										<th style="width:25%;">Account Type</th>
										<th style="width:75%;"><select name="payment_type" id="payment_type" class="form-control" style="height:40px;">
												<option value=''>Select Account Type</option>
												<option value='Savings Account'>Savings Account</option>
												<option value='Current Account'>Current Account</option>
											</select></th>
									</tr>

									<tr>
										<th style="width:25%;">Account holder name</th>
										<th style="width:75%;"><input name="card_holder" id="card_holder" type="text" class="form-control"></th>
									</tr>

									<tr>
										<th style="width:25%;">Account Number</th>
										<th style="width:75%;"><input name="card_no" id="card_no" type="text" class="form-control" value="<?php echo $rsedit['card_no']; ?>"></th>
									</tr>
									<?php
									/*
			<tr>
				<th style="width:25%;">Expiry Date</th>
				<th style="width:75%;"><input name="exp_date" id="exp_date" type="month" class="form-control" min="<?php echo $minmonth; ?>" value="<?php echo $rsedit[exp_date]; ?>"></th>
			</tr>
			*/
									?>

									<tr>
										<th style="width:25%;">SWIFT code</th>
										<th style="width:75%;"><input name="cvv_no" id="cvv_no" type="text" class="form-control" value="<?php echo $rsedit['cvv_no']; ?>"></th>
									</tr>

									<tr>
										<th style="width:25%;"></th>
										<th style="width:75%;"><input type="button" id="btnpayment" name="btnpayment" class="form-control" value="Confirm to Cancel"></th>
									</tr>


								</thead>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- iqoniq Contant Wrapper End-->
<?php
include("datatable.php");
include("footer.php");
?>
<script>
	function confirmdelete() {
		if (confirm("Are you sure you want to delete this record??") == true) {
			return true;
		} else {
			return false;
		}
	}
</script>
<script>
	$('#btnpayment').bind('click', function(e) {
		var onlynumbers = /^[0-9]*$/;
		var onlycharacter = /^[a-zA-Z\s]*$/;
		var mobno = /^[789]\d{9}$/;
		var ifscregex = /^[A-Za-z]{4}\d{7}$/;
		if (document.getElementById("payment_type").value == "") {
			alert("Kindly select Account Type..");
			return false;
		} else if (document.getElementById("card_holder").value == "") {
			alert("Kindly enter Account holder name...");
			return false;
		} else if (!document.getElementById("card_holder").value.match(onlycharacter)) {
			alert("Account holder Name should contain only Character..");
			return false;
		} else if (document.getElementById("card_no").value == "") {
			alert("Bank account number should not be empty...");
			return false;
		} else if (document.getElementById("card_no").value.length > 20) {
			alert("Bank Account number should not exceed more than 20 digit...");
			return false;
		} else if (document.getElementById("card_no").value.length < 10) {
			alert("Bank Account number should contain more than 10 digit...");
			return false;
		} else if (document.getElementById("cvv_no").value == "") {
			alert("IFSC code should not be empty....");
			return false;
		} else if (document.getElementById("cvv_no").value.length < 5) {
			alert("IFSC code should contain more than 5 digits...");
			return false;
		}
		/*
		else if(document.getElementById("exp_date").value == "")
		{
			alert("Kindly select the Expiration Date...");
			return false;
		}	
		*/
		else {

			var payment_type = $('#payment_type').val();
			var card_holder = $('#card_holder').val();
			var card_no = $('#card_no').val();
			var cvv_no = $('#cvv_no').val();
			var exp_date = $('#exp_date').val();
			var room_booking_id = $('#room_booking_id').val();
			var totalamt = $('#totalamt').val();

			$.post("payment.php", {
					'payment_type': payment_type,
					'card_holder': card_holder,
					'card_no': card_no,
					'cvv_no': cvv_no,
					'exp_date': exp_date,
					'totcost': totalamt,
					'room_booking_id': room_booking_id,
					'btncancellation': "btncancellation"
				},
				function(data, status) {
					alert("Cancellation request sent successfully.. Amount will be credited to your account within 2-3 working days..");
					window.location = 'cancellationreceipt.php?paymentid=' + data;
				});
		}
	});
</script>