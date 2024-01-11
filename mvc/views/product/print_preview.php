<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <div class="profileArea">
        <?php featureheader($siteinfos); ?>

        <div class="areaTop">
            <div class="productProfile">
                <div class="singleItem">
                    <div class="single_label">Product Name</div>
                    <div class="single_value">: <?= $product->productname ?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label">Code/Ref.</div>
                    <div class="single_value">: <?= $product->code_reference ?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label">Description</div>
                    <div class="single_value">: <?= $product->productdesc ?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label">Category</div>
                    <div class="single_value">: <?= isset($productcategorys[$product->productcategoryID]) ? $productcategorys[$product->productcategoryID] : '' ?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label">Buying Price</div>
                    <div class="single_value">: <?= $siteinfos->currency_code . ' ' . number_format((float)($product->productbuyingprice), 2, '.', '00') ?></div>
                </div>
            </div>
        </div>

        <div class="areaBottom">
            <table class="table table-bordered">
                <thead style='background: #e5e5e5'>
                    <tr>
                        <th>Sr. #</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (customCompute($allquotations)) {
                        $sr = 1;
                        foreach ($allquotations as $quoation) {
                    ?>
                            <tr class='text-center'>
                                <td><?= $sr++ ?></td>
                                <td><?= $quoation->description ?></td>
                                <td><?= $quoation->quantity ?></td>
                                <td><?= number_format((float)$quoation->unit_price, 2, '.', ''); ?></td>
                                <td><?= $siteinfos->currency_code . ' ' . number_format((float)($quoation->unit_price * $quoation->quantity), 2, '.', '00')  ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <?php featurefooter($siteinfos); ?>
</body>

</html>