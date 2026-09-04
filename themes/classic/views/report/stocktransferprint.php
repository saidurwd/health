<?php
$this->pageTitle = 'Stock Transfer Report';
$start_date = @$_REQUEST['start_date'];
$end_date = @$_REQUEST['end_date'];
?>
<div class="widget-body no-padding">
    <div class="padding-10">
        <div class="pull-left">
            <?php echo Company::get_logo(Yii::app()->user->companyid); ?>
            <br /><br />
            <?php echo Company::get_address_details(Yii::app()->user->companyid); ?>
        </div>
        <div class="pull-right">
            <h1 class="font-400">Stock Transfer</h1>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-9">

            </div>
            <div class="col-sm-3">                
                <div>
                    <div>
                        <strong>START :</strong>
                        <span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo Client::get_date(@$start_date); ?> </span>
                    </div>
                </div>
                <div>
                    <div>
                        <strong>END :</strong>
                        <span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo Client::get_date(@$end_date); ?> </span>
                    </div>
                </div>                
                <br />
                <div class="well well-sm  bg-color-darken txt-color-white no-border">
                    <span class="pull-right"> </span>
                </div>
            </div>
            <br />
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Transfer#</th>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $array = Report::stockTransferReport($start_date, $end_date);
            $total = count($array);
            foreach ($array as $key => $value) {
                echo '<tr>';
                echo '<td>' . $value["transfer_number"] . '</td>';
                echo '<td>' . Client::get_date($value["transfer_date"]) . '</td>';
                echo '<td>' . Store::get_full_path($value["store_from"]) . '</td>';
                echo '<td>' . Store::get_full_path($value["store_to"]) . '</td>';
                echo '<td>' . $value["item"] . '</td>';
                echo '<td>' . $value["quantity"] . ' ' . Item::getItemUOM($value["itemid"]) . '</td>';
                echo '<td class="text-right">' . Item::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
                echo '<td class="text-right">' . Item::number_format_currency($value["total"], 2, Yii::app()->session->get("currency")) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <div class="summary space-top-10">Displaying <?php echo $total; ?> results.</div>
    <br />
</div>
<div class="invoice-footer space-top-10">
    <div class="row">
        <div class="col-sm-12 text-right">
            <p class="note"><?php echo Yii::app()->params['print_note']; ?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>
<style>
    body{
        font-size: 10px;
    }
    .font-size{
        font-size: 10px;
    }
    .text-center{
        text-align: center;
    }
</style>