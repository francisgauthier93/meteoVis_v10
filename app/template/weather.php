<div class="row page-header ">
    <?php echo $sHead; ?>
</div>

<div class="row">
    <?php echo $sBody; ?>

    <div class="clearfix visible-sm"></div>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <table class="table table-condensed">
            <colgroup>
                <col style="width:50%"/>
                <col style="width:50%"/>
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2" data-original-translation="">For the next 3 hours</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    echo $sNextHours;
                ?>
            </tbody>
        </table>
    </div>
</div>