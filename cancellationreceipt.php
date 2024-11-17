<?php
include("header.php");
$sqlhotelcancel = "SELECT * FROM payment WHERE status='Cancelled' AND payment_id='$_GET[paymentid]'";
$qsqlhotelcancel = mysqli_query($con, $sqlhotelcancel);
$rshotelcancel = mysqli_fetch_array($qsqlhotelcancel);

$totalamt = 0;
?>
<!-- Sub Banner Start -->
<div class="mg_sub_banner">
	<div class="container">
		<h2>Cancellation Receipt</h2>
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
							<center><b style="font-size:25px;color:red;">Cancellation Receipt</b></center>
							<table class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Cancellation Receipt No.</th>
										<th>Cancellation Date</th>
										<th>Cancellation Time</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $rshotelcancel['payment_id']; ?></td>
										<td><?php echo date("d-M-Y", strtotime($rshotelcancel['payment_date'])); ?></td>
										<td><?php echo date("h:i A", strtotime($rshotelcancel['payment_time'])); ?></td>
									</tr>
								</tbody>
							</table>
							<hr>
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
									$sql = "SELECT * FROM room_booking LEFT JOIN hotel ON room_booking.hotel_id=hotel.hotel_id LEFT JOIN room_type ON room_booking.room_typeid=room_type.room_typeid LEFT JOIN customer ON room_booking.customer_id=customer.customer_id LEFT JOIN payment ON payment.room_booking_id=room_booking.room_booking_id WHERE payment.status='Cancel' AND payment.food_order_id='0' AND payment.cab_bookingid='0' AND payment.room_booking_id='$rshotelcancel[name]' ";
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
			<td>$rs[room_type]<br>(Rs. $rs[cost] / day)</td>";
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
			<td>Rs. " . $rs['cost'] * $nodays . "</td>
			<td>Rs. ";
										echo $rs['cost'] * $nodays / 2;
										echo "</td></tr>";
										$totalamt = $totalamt + ($rs['cost'] * $nodays);
									}
									?>
								</tbody>
							</table>
							<hr>



							<hr>
							<input type="hidden" name="room_booking_id" id="room_booking_id" value="<?php echo $room_booking_id; ?>">
							<input type="hidden" name="totalamt" id="totalamt" value="<?php echo $totalamt; ?>">
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
										<th colspan="2" style='color:red;'>Accout detail... </th>
									</tr>

									<tr>
										<th style="width:25%;">Payment Type</th>
										<th style="width:75%;"><?php echo $rshotelcancel['payment_type']; ?></th>
									</tr>

									<tr>
										<th style="width:25%;">Card holder</th>
										<th style="width:75%;"><?php echo $rshotelcancel['card_holder']; ?></th>
									</tr>

									<tr>
										<th style="width:25%;">Card No</th>
										<th style="width:75%;"><?php echo $rshotelcancel['card_no']; ?></th>
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
include("footer.php");
?>