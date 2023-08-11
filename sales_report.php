<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">
            <div class="row justify-content-center pt-4">
                <label for="" class="mt-2">Month</label>
                <div class="col-sm-3">
                    <input type="month" name="month" id="month" value="<?php echo $month ?>" class="form-control">
                </div>
            </div>
            <hr>
            <div class="col-md-12">
                <table class="table table-bordered" id='report-list'>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="">Date</th>
                            <th class="">Invoice</th>
                            <th class="">Order Number</th>
                            <th class="">Staff</th>
                            <th class="">Amount</th>
                        </tr>
                    </thead>
                    <!--  Orginal Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  
                    <tbody>
			          <?php
                        $i = 1;
                        $total = 0;
                        $loginName = $_SESSION['login_name'];
                        $userType = $_SESSION['login_type'];

                        if ($userType == 1) {
                            // Admin user: Retrieve all staff orders with amount tendered > 0 for the specified month
                            $salesQuery = "SELECT o.*, u.name 
                                            FROM orders o
                                            LEFT JOIN users u ON o.id_user = u.id
                                            WHERE o.amount_tendered > 0 
                                            AND date_format(o.date_created, '%Y-%m') = '$month' 
                                            ORDER BY UNIX_TIMESTAMP(o.date_created) ASC";
                        } else {
                            // Staff user: Retrieve orders associated with the logged-in user with amount tendered > 0 for the specified month
                            $salesQuery =   "SELECT o.*, u.name 
                                                        FROM orders o
                                                        LEFT JOIN users u ON o.id_user = u.id
                                                        WHERE o.amount_tendered > 0 
                                                        AND date_format(o.date_created, '%Y-%m') = '$month' 
                                                        AND o.id_user = (SELECT id FROM users us WHERE us.name = '$loginName') 
                                                        ORDER BY UNIX_TIMESTAMP(o.date_created) ASC";
                        }

                            $sales = $conn->query($salesQuery);
                      
                            if($sales->num_rows > 0):
			                while($row = $sales->fetch_array()):
                            $total += $row['total_amount'];
			          ?>
			          <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td>
                            <p> <b><?php echo date("M d,Y",strtotime($row['date_created'])) ?></b></p>
                        </td>
                        <td>
                            <p> <b><?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></b></p>
                        </td>
                        <td>
                            <p> <b><?php echo $row['order_number'] ?></b></p>
                        </td>
                        <td>
                            <p> <b><?php echo $row['name'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo number_format($row['total_amount'],2) ?></b></p>
                        </td>


                        
                    </tr>
                    <?php 
                        endwhile;
                        else:
                    ?>
                    <tr>
                            <th class="text-center" colspan="5">No Data.</th>
                    </tr>
                    <?php 
                        endif;
                    ?>
			        </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right"><?php echo number_format($total,2) ?></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="col-md-12 mb-4">
                    <center>
                        <button class="btn btn-success btn-sm col-sm-3" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    </center>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<noscript>
	<style>
		table#report-list{
			width:100%;
			border-collapse:collapse
		}
		table#report-list td,table#report-list th{
			border:1px solid
		}
        p{
            margin:unset;
        }
		.text-center{
			text-align:center
		}
        .text-right{
            text-align:right
        }
	</style>
</noscript>
<script>
$('#month').change(function(){
    location.replace('index.php?page=sales_report&month='+$(this).val())
})
$('#print').click(function(){
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Order Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
</script>
<!--  Orginal Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website  - www.mayurik.com  -->  