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
            SELECT SUM(amount_paid),COUNT(stat) as totol,DATE_FORMAT(dttran,'%M-%Y') AS dttran
            FROM csop_rep_import 
            WHERE stat = 'C' AND dttran BETWEEN '2020-01-01' and '2021-12-31'
             GROUP BY DATE_FORMAT(dttran, '%m%')
            ORDER BY DATE_FORMAT(dttran, '%m%')
                        
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
            <h3 align="center">รายงานในแบบกราฟจำนวนการติด C </h3>
            
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
            <hr>
            <p align="center">
                <!--jUON.com-->
                <canvas id="myChart" width="800px" height="300px"></canvas>
                <script>
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                labels: [<?php echo $dttran;?>
                
                ],
                datasets: [{
                label: 'รายงานรายได้ แยกตามเดือน (บาท)',
                data: [<?php echo $totol;?>
                ],
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(17, 192, 152, 0.2)',
                'rgba(72, 102, 142, 0.2)',
                'rgba(205, 142, 132, 0.2)',
                'rgba(73, 152, 122, 0.2)',
                'rgba(75, 102, 12, 0.2)',
                'rgba(20, 172, 102, 0.2)',
                ],
                borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(17, 192, 152, 1)',
                'rgba(78, 182, 122, 1)',
                'rgba(205, 142, 132, 1)',
                'rgba(10, 152, 152, 1)',
                'rgba(75, 102, 12, 1)',
                'rgba(75, 112, 590, 1)',
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
            <div class="col-sm-7">
                <h3>ตารางการติด C</h3>
                <table  class="table table-striped" border="1" cellpadding="0"  cellspacing="0" align="center">
                    <thead>
                        <tr class="table-primary">
                            <th width="30%">ด/ป</th>
                            <th width="70%"><center>จำนวนที่ติด C</center></th>
                        </tr>
                    </thead>
                    
                    <?php while($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td> -<?php echo $row['dttran'];?></td>
                        <td align="right"><?php echo number_format($row['totol']);?></td>
                    </tr>
                    <?php
                    @$amount_total += $row['totol'];
                    }
                    ?>
                    <tr class="table-danger">
                        <td align="center">รวม</td>
                        <td align="right"><b>
                        <?php echo number_format($amount_total,2);?></b></td></td>
                    </tr>
                </table>
            </div>
            <?php mysqli_close($con);?>
        </div>
    </div>
</div>