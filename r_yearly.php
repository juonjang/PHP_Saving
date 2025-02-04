<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $query = "
            SELECT amount_paid, SUM(amount_paid) AS totol, DATE_FORMAT(dttran, '%Y') AS dttran
            FROM csop_rep_import
            GROUP BY DATE_FORMAT(dttran, '%Y%')
            ORDER BY DATE_FORMAT(dttran, '%Y') DESC
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
            <h3 align="center">รายงานในแบบกราฟ by Juon.com</h3>
            
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
            <hr>
            <p align="center">
                <!--Juon.com-->
                <canvas id="myChart" width="800px" height="300px"></canvas>
                <script>
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: [<?php echo $dttran;?>
                
                ],
                datasets: [{
                label: 'รายงานรายได้ แยกตามปี (บาท)',
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
            <div class="col-sm-4">
                <h3>List</h3>
                <table  class="table table-striped" border="1" cellpadding="0"  cellspacing="0" align="center">
                    <thead>
                        <tr class="table-primary">
                            <th width="30%">ว/ด/ป</th>
                            <th width="70%"><center>รายได้</center></th>
                        </tr>
                    </thead>
                    
                    <?php while($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['dttran'];?></td>
                        <td align="right"><?php echo number_format($row['totol'],2);?></td>
                    </tr>
                    <?php
                    @$amount_paid_total += $row['totol'];
                    }
                    ?>
                    <tr class="table-danger">
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