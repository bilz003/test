<?php
use \Cake\Utility\Inflector;

$name = $this->name;
$kendoGridID = $cName . "Grid";
$prefix = $this->request['prefix'];
?>


<div class="user index">
    <div id="messages"></div>
    <div id="<?php echo $kendoGridID; ?>"></div>
</div>


<script>
    <?php echo $this->Html->scriptStart(['block' => true]);?>
    <?php echo $kendoDsData; ?>
    <?php echo $kendoEdData; ?>
    $(document).ready(function () {
        errorMessageShown = false;
        mainData = new kendo.data.DataSource({
            error: function (e) {
                if (errorMessageShown == false) {
                    alert(e.errors.message);
                    errorMessageShown = true;
                }
                $("#<?php echo $kendoGridID; ?>").data().kendoGrid.one('dataBinding', function (e) {
                    e.preventDefault();
                })
            },
            requestEnd: function (e) {
                var response = e.response;
                var type = e.type;

                if (type == "create" || type == "update") {
                    if (!response.success) {
                        var message = response.message;
                        if (response.validationErrors) {
                            message += '</br>';
                            $.each(response.validationErrors, function (key, value) {
                                message += '<div style="background-color: indianred;">';
                                message += '    <b>' + key + '</b>';
                                $.each(value, function (key2, value2) {
                                    message += '<div style="margin-left: 5px;">';
                                    message += key2 + ': <i>' + value2 + '</i>';
                                    message += '</div>';
                                });
                                message += '</div>';
                            });
                        }
                        toastr["error"](message);
                        $("#<?= $kendoGridID; ?>").data().kendoGrid.one('dataBinding', function (e) {
                            e.preventDefault();
                        });
                    } else {
                        toastr["success"](response.message);
                        $("#<?= $kendoGridID; ?>").data("kendoGrid").dataSource.read();
                    }
                }
            },
            transport: {
                read: {
                    url: "\/db/api/<?= $prefix . '/' . $cName; ?>.json",
                    type: "get",
                    dataType: "json",
                    data: {}
                },
                create: {
                    url: "/db/api/<?= $prefix . '/' . $cName; ?>.json",
                    type: "post",
                    dataType: "json",
                    data: {}
                },
                update: {
                    url: function (data) {
                        return "/db/api/<?= $prefix . '/' . $cName; ?>/" + data.models[0].<?= $name;?>_id + ".json"
                    },
                    type: "PUT",
                    dataType: "json",
                    data: {}
                },
                destroy: {
                    url: function (data) {
                        var newdata = data.models;
                        var count = newdata.length - 1;
                        return "/db/api/<?= $prefix . '/' . $cName; ?>/" + data.models[count].<?= $name;?>_id + ".json"
                    },
                    type: "DELETE"
                },
                parameterMap: function (data, operation) {
                    if (operation == "read") {
                        return {
                            page: data.page,
                            skip: data.skip,
                            limit: data.pageSize,
                            cOrder: data.sort,
                            cFilter: data.filter
                        }
                    }
                    if (operation !== "read" && data.models) {
                        return {action: operation, models: kendo.stringify(data.models)};
                    }
                }
            },
            schema: {
                model:<?php echo $kendoModelArray; ?>,
                data: "<?php echo $cName; ?>.children",
                total: function (response) {
                    return response.<?= $cName;?>.paging.<?= $cName;?>.count;
                }
            },
            batch: true,
            page: 1,
            pageSize: 100,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
        });
        <?php
        $dataOptions['gridDivID'] = $kendoGridID;
        $dataOptions['columns'] = $kendoGridCols;
        $dataOptions['toolbar'] = "[{
                        template: 
                        '<div>'+
                        '   <div style=\"float:left;\">'+
                        '       <h2>" . Inflector::humanize(Inflector::underscore($cName)) . "</h2>'+
                        '   </div>'+
                        '   <div style=\"float:right;margin: 2% 1% 1% 1%;\">'+
                        '       <a title=\"Add new\" class=\"k-button k-button-icontext k-grid-add\" href=\"\\\\#\">'+
                        '           <span class=\"k-icon k-i-plus-outline\"></span>'+
                        '           Add'+
                        '       </a>'+
                        '   </div>'+
                        '</div>'
                        }]";
        $dataOptions['editable'] = "{
            mode: 'popup',
        }";
        $dataOptions['edit'] = "function(e){
            showToolTip();
        }";
        $customArray['save'] = "function(e){
        }
        ";
        echo $this->Kendo->createGrid($cName, $dataOptions, $customArray) . "\n";
        echo $this->Kendo->gridFunctions($cName) . "\n";
        ?>
    });
    <?php echo $this->Html->scriptEnd();?>
</script>

