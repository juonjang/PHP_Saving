<script>
function hidestatus() {
window.status='';
return true;
}
document.onmouseover = hidestatus;
document.onmouseout = hidestatus;
</script>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $query = "
            SELECT  filename,amount_paid, SUM(amount_paid) AS totol, DATE_FORMAT(dttran, '%d-%M-%Y') AS dttran
            FROM csop_rep_import INNER JOIN patient ON csop_rep_import.hn = patient.hn WHERE dttran BETWEEN '2020-10-01' AND '2021-12-31' 
            GROUP BY DATE_FORMAT(dttran, '%d%')
            ORDER BY DATE_FORMAT(dttran, '%Y-%m-%d') DESC
            ";
            $result = mysqli_query($con, $query);
            $resultchart = mysqli_query($con, $query);
            //for chart
            $dttran = array();
            $totol = array();
            while($rs = mysqli_fetch_array($resultchart)){
            $dttran[] = "\"".$rs['dttran']."\"";
            $totol[] = "\"".$rs['totol']."\"";
            }
            $dttran = implode(",", $dttran);
            $totol = implode(",", $totol);
            
            ?>
            <h3 align="center">รายงานในแบบกราฟ </h3>
            
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
            <hr>
            <p align="center">
                <!--JuonTable.com-->
                <canvas id="myChart" width="800px" height="300px"></canvas>
                <script>
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: [<?php echo $dttran;?>
                
                ],
                datasets: [{
                label: 'รายงานรายได้ แยกตามวัน (บาท)',
                data: [<?php echo $totol;?>
                ],
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
                }]
                },
                options: {
                scales: {
                yAxes: [{
                ticks: {
                beginAtZero:true
                }
                }]
                }
                }
                });
                </script>
            </p>
            <div class="col-sm-12">
                <h3>List</h3>
                <table  class="table table-striped" border="1" cellpadding="0"  cellspacing="0" align="center">
                    <thead>
                        <tr class="table-primary">
                            <th width="20%">ว/ด/ป</th>
                            <th width="50%">รายละเอียด</th>
                            <th width="10%"><center>รายได้</center></th>
                        </tr>
                    </thead>
                    
                    
                    <?php 
					
		   $sql = "
            SELECT * FROM csop_rep_import
            ORDER BY filename DESC  Limit 100 
            ";
            $result2 = mysqli_query($con, $sql);
					while($row2 = mysqli_fetch_array($result2)) { 
					
					?>
                    <tr>
                        <td><?php echo $row2['dttran'];?></td>
                        <td><?php echo $row2['filename'];?></td>
                        <td align="right"><?php echo number_format($row2['amount_paid'],2);?></td>
                    </tr>
                    <?php
                    @$amount_paid_total += $row2['amount_paid'];
                    }
                    ?>
                    <tr class="table-danger">
                         <td align="center"></td>
                        <td align="center">รวม</td>
                        <td align="right"><b>
                        <?php echo number_format($amount_paid_total,2);?></b></td></td>
                    </tr>
                </table>
            </div>
            <?php mysqli_close($con);?>
        </div>
    </div>
</div>