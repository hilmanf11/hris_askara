<style>
    .swal2-modal-over-modal {
        z-index: 9999 !important;
    }

    .swal2-backdrop-show {
        z-index: 9998 !important;
    }
</style>
<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar" data-options="rownumbers:true, singleSelect:false, fit:true, idField:'date_in', autoRowHeight:false, pageSize:20, pageList:[20,50,100,200], pagination:true">
    <thead>
        <tr>
            <th rowspan="2" field="ck" checkbox="true"></th>
            <th rowspan="2" data-options="field:'id',width:150">ID</th>
            <th rowspan="2" data-options="field:'permit_name',width:150">Permit Type</th>
            <th rowspan="2" data-options="field:'reason_name',width:150">Reason Name</th>
            <th rowspan="2" data-options="field:'effective_date',width:200">Effective Date</th>
            <th rowspan="2" data-options="field:'description',width:120">Description</th>
            <th colspan="2" data-options="width:200">Create</th>
            <th colspan="2" data-options="width:100">Update</th>
        </tr>
        <tr>
            <th data-options="field:'created_by',width:150">By</th>
            <th data-options="field:'created_date',width:150">Date</th>
            <th data-options="field:'updated_by',width:150">By</th>
            <th data-options="field:'updated_date',width:200">Date</th>
    </thead>
</table>

<!-- TOOLBAR DATAGRID -->
<div id="toolbar">
    <div class="easyui-accordion" style="width:100%; margin-bottom:5px;">
        <div title="Click this to hide the filter" data-options="onCollapse: function(){ $('#dg').datagrid('reload'); }" style="padding:10px; background:#F4F4F4;">
            <fieldset style="width: 99%; border:2px solid #d0d0d0; border-radius:4px;">
                <legend><b>Form Filter Data</b></legend>
                <div style="width: 50%; float: left;">
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Effective Date</span>
                        <input style="width:28%;" name="filter_from" id="filter_from" value="<?= date("Y-m-01") ?>" data-options="formatter:myformatter,parser:myparser, editable: false" class="easyui-datebox"> To
                        <input style="width:28%;" name="filter_to" id="filter_to" value="<?= date("Y-m-t") ?>" data-options="formatter:myformatter,parser:myparser, editable: false" class="easyui-datebox">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Permit Type</span>
                        <input style="width:60%;" id="filter_permit_type" class="easyui-combobox">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Reason Name</span>
                        <input style="width:60%;" id="filter_reason" class="easyui-combobox">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;"></span>
                        <a href="javascript:;" class="easyui-linkbutton" onclick="filter()"><i class="fa fa-search"></i> Filter Data</a>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <?= $button ?>
</div>

<!-- DIALOG SAVE AND UPDATE -->
<div id="dlg_insert" class="easyui-dialog" title="Add New" data-options="closed: true,modal:true" style="width: 1100px; height: 500px; padding:10px; top: 20px;">
    <form id="frm_insert" method="post" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div style="width: 50%; float: left;">
                <div class="fitem">
                    <span style="width:35%; display:inline-block;">Permit Type</span>
                    <input style="width:60%;" id="permit_type_id" name="permit_type_id" required class="easyui-combogrid">
                </div>
                <div class="fitem">
                    <span style="width:35%; display:inline-block;">Reason Name</span>
                    <input style="width:60%;" id="reason_id" name="reason_id" required class="easyui-combogrid">
                </div>
                <div class="fitem">
                    <span style="width:35%; display:inline-block;">Efective Date</span>
                    <input style="width:60%;" name="effective_date" id="effective_date" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
                </div>
                <div class="fitem">
                    <span style="width:35%; display:inline-block;">Description</span>
                    <input style="width:60%;" id="description" name="description" class="easyui-textbox">
                </div>
                <input type="hidden" id="detail_data" name="detail_data">
                <input type="hidden" id="deleted_ids" name="deleted_ids">
            </div>
        </fieldset>
        <table id="dg2" class="easyui-datagrid" style="width:100%;" title="Employee Lists" toolbar="#toolbar2"></table>
    </form>
</div>

<div id="toolbar2">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" id="addrow" onclick="append()">
        <i class="fa fa-plus"></i> Add</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="removeRow()">
        <i class="fa fa-trash"></i> Remove
    </a>
</div>

<div id="dlg_upload" class="easyui-dialog" title="Upload Data" data-options="closed: true,modal:true" style="width: 600px; padding:10px; top: 20px;">
    <form id="frm_upload" method="post" enctype="multipart/form-data" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Permit Type</span>
                <input style="width:60%;" id="upermit_type_id" name="upermit_type_id" required class="easyui-combogrid">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Reason Name</span>
                <input style="width:60%;" id="ureason_id" name="ureason_id" required class="easyui-combogrid">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Efective Date</span>
                <input style="width:60%;" name="ueffective_date" id="ueffective_date" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Description</span>
                <input style="width:60%;" id="udescription" name="udescription" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">File Type</span>
                <select style="width:60%;" name="file_type" id="file_type" panelHeight="auto" class="easyui-combobox">
                    <option value="excel">Excel</option>
                </select>
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">File Upload</span>
                <input name="file_upload" style="width: 60%;" required="" accept=".xls" id="file_excel" class="easyui-filebox">
            </div>
        </fieldset>
    </form>
    <span style="float: left; color:green;">SUCCESS : <b id="p_success">0</b></span><span style="float: right; color:red;"> FAILED : <b id="p_failed">0</b></span>
    <div id="p_upload" class="easyui-progressbar" style="width:100%; margin-top: 10px;"></div>
    <center><b id="p_start">0</b> Of <b id="p_finish">0</b></center>
    <div id="p_remarks" title="History Upload" class="easyui-panel" style="width:100%; height:200px; padding:10px; margin-top: 10px;">
        <ul id="remarks">

        </ul>
    </div>
</div>


<!-- Forecast Customer -->
<div id="dlg_employee" class="easyui-dialog" title="Add Employee" data-options="closed: true,modal:true" style="width: 430px; height: 580px; padding:10px; top: 100px;">
    <table id="dg_employee" class="easyui-datagrid" style="width:100%;"></table>
</div>

<!-- PDF -->
<iframe id="printout" src="" style="width: 100%;" hidden></iframe>

<script>
    var lastIndex = undefined;
    var deletedDetailIds = [];
    //ADD DATA
    function add() {
        $('#dlg_insert').dialog('open');
        $('#dg_employee').datagrid('clearChecked');
        url_save = '<?= base_url('attandance/assign_time_off/create') ?>';
        $('#frm_insert').form('clear');
        $("#permit_type_id").combobox('setValue', '20221213000002');
        $("#reason_id").combobox('setValue', '20221213000002');
        deletedDetailIds = [];
        $('#dg2').datagrid('loadData', []);
    }

    //EDIT DATA
    function update() {
        var row = $('#dg').datagrid('getSelected');
        deletedDetailIds = [];
        if (row) {
            $('#dlg_insert').dialog('open').dialog('setTitle', 'Edit Permit');
            $('#frm_insert').form('load', row);
            $('#dg2').datagrid('loadData', []);
            $('#dg_employee').datagrid('clearChecked');

            $('#dg2').datagrid('options').url = '<?= base_url("attandance/assign_time_off/datatableDetails/") ?>' + row.id;
            $('#dg2').datagrid('reload');
            $('#frm_insert').data('id', row.id);

            url_save = '<?= base_url('attandance/assign_time_off/update') ?>?id=' + window.btoa(row.id);
        } else {
            toastr.warning("Please select one of the data in the table first!", "Information");
        }
    }

    //DELETE DATA
    function deleted() {
        var rows = $('#dg').datagrid('getSelections');
        if (rows.length > 0) {
            $.messager.confirm('Warning', 'Are you sure you want to delete this data?', function(r) {
                if (r) {
                    for (var i = 0; i < rows.length; i++) {
                        var row = rows[i];
                        $.ajax({
                            method: 'post',
                            url: '<?= base_url('attandance/assign_time_off/delete') ?>',
                            data: {
                                id: row.id,
                            },
                            success: function(result) {
                                var result = eval('(' + result + ')');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                toastr.error(jqXHR.statusText);
                                $.messager.alert("Error", jqXHR.statusText, 'error');
                            },
                            complete: function(data) {
                                $('#dg').datagrid('reload');
                            }
                        });
                    }
                }
            });
        } else {
            toastr.warning("Please select one of the data in the table first!", "Information");
        }
    }

    //UPLOAD DATA
    function upload() {
        $('#dlg_upload').dialog('open');
        $("#upermit_type_id").combobox('setValue', '20221213000002');
        $("#ureason_id").combobox('setValue', '20221213000002');
    }

    function download_excel() {
        window.location.assign('<?= base_url('template/tmp_assign_time_off.xls') ?>');
    }

    function filter() {
        var filter_from = $("#filter_from").datebox('getValue');
        var filter_to = $("#filter_to").datebox('getValue');
        var filter_permit_type = $("#filter_permit_type").combobox('getValue');
        var filter_reason = $("#filter_reason").combobox('getValue');

        var url = "?filter_from=" + filter_from +
            "&filter_to=" + filter_to +
            "&filter_permit_type=" + filter_permit_type +
            "&filter_reason=" + filter_reason;

        $('#dg').datagrid({
            url: '<?= base_url('attandance/assign_time_off/datatables') ?>' + url,
            view: detailview,
            detailFormatter: function(index, row) {
                return '<div style="padding:2px;position:relative;"><table class="ddv" title="Detail"></table></div>';
            },
            onExpandRow: function(index, row) {
                var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');
                ddv.datagrid({
                    url: '<?= base_url('attandance/assign_time_off/datatableDetails/') ?>' + row.id,
                    singleSelect: true,
                    rownumbers: true,
                    columns: [
                        [{
                                field: 'employee_id',
                                hidden: false,
                                width: 200,
                                halign: 'center',
                                title: "Employee ID",
                            },
                            {
                                field: 'employee_name',
                                hidden: false,
                                width: 200,
                                halign: 'center',
                                title: "Full Name",
                            }, {
                                field: 'balance',
                                width: 100,
                                halign: 'center',
                                title: "Input Balance",
                            }, {
                                field: 'start_date',
                                width: 120,
                                halign: 'center',
                                title: "Start Date",
                            }, {
                                field: 'end_date',
                                width: 120,
                                halign: 'center',
                                title: "End Date",
                            },
                        ]
                    ],
                    onResize: function() {
                        $('#dg').datagrid('fixDetailRowHeight', index);
                    },
                    onLoadSuccess: function() {
                        setTimeout(function() {
                            $('#dg').datagrid('fixDetailRowHeight', index);
                        }, 0);
                    }
                });
                $('#dg').datagrid('fixDetailRowHeight', index);
            }
        });

        $("#printout").contents().find('html').html("<center><br><br><br><b style='font-size:20px;'>Please Wait...</b></center>");
        $("#printout").attr('src', '<?= base_url('attandance/assign_time_off/print') ?>' + url);
    }

    //PRINT PDF
    function pdf() {
        $("#printout").get(0).contentWindow.print();
    }
    //PRINT EXCEL
    function excel() {
        var filter_from = $("#filter_from").datebox('getValue');
        var filter_to = $("#filter_to").datebox('getValue');
        var filter_permit_type = $("#filter_permit_type").combobox('getValue');
        var filter_reason = $("#filter_reason").combobox('getValue');

        var url = "?filter_from=" + filter_from +
            "&filter_to=" + filter_to +
            "&filter_permit_type=" + filter_permit_type +
            "&filter_reason=" + filter_reason;

        window.location.assign('<?= base_url('attandance/assign_time_off/print/excel') ?>' + url);

    }
    //RELOAD
    function reload() {
        window.location.reload();
    }

    $(function() {
        $('#dlg_insert').dialog({
            buttons: [{
                text: 'Save',
                iconCls: 'icon-ok',
                handler: function() {
                    $('#frm_insert').form('submit', {
                        url: url_save,
                        onSubmit: function() {
                            if (!$(this).form('validate')) {
                                toastr.warning('Please complete the master form before saving.', "Information");
                                return false;
                            }
                            if (!endAllEditing()) {
                                toastr.warning('Please complete all entries in the employee table before saving.', "Information");
                                return false;
                            }

                            var detail = $('#dg2').datagrid('getRows');
                            if (detail.length === 0) {
                                toastr.warning('There must be at least 1 employee record.', "Information");
                                return false;
                            }

                            $('#detail_data').val(JSON.stringify(detail));
                            $('#deleted_ids').val(JSON.stringify(deletedDetailIds));

                            return true;
                        },
                        success: function(result) {
                            var result = eval('(' + result + ')');

                            if (result.theme == "success") {
                                toastr.success(result.message, result.title);
                                $('#dlg_insert').dialog('close');
                                $('#dg').datagrid('reload');
                            } else {
                                toastr.error(result.message, result.title);
                            }

                        }
                    });
                }
            }]
        });


        //UPLOAD DATA
        $('#dlg_upload').dialog({
            buttons: [{
                text: 'List Failed',
                handler: function() {
                    window.open('<?= base_url('attandance/assign_time_off/uploadDownloadFailed') ?>', '_blank');
                }
            }, {
                text: 'Upload',
                iconCls: 'icon-ok',
                handler: function() {
                    $('#frm_upload').form('submit', {
                        url: '<?= base_url('attandance/assign_time_off/upload') ?>',
                        onSubmit: function() {
                            if ($(this).form('validate') == false) {
                                return $(this).form('validate');
                            } else {
                                $.messager.progress({
                                    title: 'Please Wait',
                                    msg: 'Importing Excel to Database'
                                });
                            }
                        },
                        success: function(result) {
                            $.messager.progress('close');
                            //Clear File
                            $.ajax({
                                url: "<?= base_url('attandance/assign_time_off/uploadclearFailed') ?>"
                            });

                            var json = eval('(' + result + ')');
                            requestData(json.total, json);

                            function requestData(total, json, number = 1, value = 0, success = 1, failed = 1) {
                                if (value < 100) {
                                    value = Math.floor((number / total) * 100);
                                    $('#p_upload').progressbar('setValue', value);
                                    $('#p_start').html(number);
                                    $('#p_finish').html(total);

                                    $.ajax({
                                        type: "POST",
                                        async: true,
                                        url: "<?= base_url('attandance/assign_time_off/uploadCreate') ?>",
                                        data: {
                                            "data": json[number - 1]
                                        },
                                        cache: false,
                                        dataType: "json",
                                        success: function(result) {
                                            if (result.theme == "success") {
                                                $('#p_success').html(success);
                                                var title = "<b style='color: green;'>" + result.title + "</b> | " + result.message;
                                                requestData(total, json, number + 1, value, success + 1, failed + 0);
                                            } else {
                                                $('#p_failed').html(failed);
                                                var title = "<b style='color: red;'>" + result.title + "</b> | " + result.message;

                                                //Json Failed
                                                $.ajax({
                                                    type: "POST",
                                                    async: true,
                                                    url: "<?= base_url('attandance/assign_time_off/uploadcreateFailed') ?>",
                                                    data: {
                                                        data: json[number - 1],
                                                        message: result.message
                                                    },
                                                    cache: false
                                                });

                                                requestData(total, json, number + 1, value, success + 0, failed + 1);
                                            }

                                            $("#p_remarks").append(title + "<br>");
                                        },
                                        fail: function(jqXHR, textStatus) {
                                            Swal.fire({
                                                title: 'Connection Time Out, Check Your Connection',
                                                showConfirmButton: false,
                                                allowOutsideClick: false,
                                                allowEscapeKey: false,
                                                didOpen: () => {
                                                    Swal.showLoading();
                                                },
                                            });

                                            setTimeout(function() {
                                                requestData(total, json, number, value, success + 0, failed + 0);
                                            }, 5000);
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            }]
        });

        $("#filter_permit_type").combobox({
            url: '<?= base_url('attandance/permit_types/reads') ?>',
            valueField: 'id',
            textField: 'name',
            prompt: "Choose Permit Type",
            onSelect: function(permit) {
                $("#filter_reason").combobox({
                    url: '<?= base_url('attandance/reasons/reads') ?>?permit_type_id=' + permit.id,
                    valueField: 'id',
                    textField: 'name',
                    prompt: 'Choose Reason',
                });
            }
        });

        $("#permit_type_id").combobox({
            url: '<?= base_url('attandance/permit_types/reads') ?>',
            valueField: 'id',
            textField: 'name',
            prompt: "Choose Permit Type",
            onSelect: function(permit) {
                $("#reason_id").combobox({
                    url: '<?= base_url('attandance/reasons/reads') ?>?permit_type_id=' + permit.id,
                    valueField: 'id',
                    textField: 'name',
                    prompt: 'Choose Reason',
                });
            }
        });

        $("#upermit_type_id").combobox({
            url: '<?= base_url('attandance/permit_types/reads') ?>',
            valueField: 'id',
            textField: 'name',
            prompt: "Choose Permit Type",
            onSelect: function(permit) {
                $("#ureason_id").combobox({
                    url: '<?= base_url('attandance/reasons/reads') ?>?permit_type_id=' + permit.id,
                    valueField: 'id',
                    textField: 'name',
                    prompt: 'Choose Reason',
                });
            }
        });
    });

    //Format Datepicker
    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }

    //Format Datepicker
    function myparser(s) {
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }

    $(function() {
        $('#dg_employee').datagrid({
            url: '<?= base_url('attandance/assign_time_off/datatablesEmployee') ?>',
            pagination: true,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: true,
            fit: true,
            pageList: [20, 50, 100, 500, 1000],
            pageSize: 20,
            columns: [
                [{
                        field: 'ck',
                        width: 150,
                        halign: 'center',
                        checkbox: true
                    },
                    {
                        field: 'number',
                        title: 'Employee ID',
                        halign: 'center',
                        width: 120
                    },
                    {
                        field: 'name',
                        title: 'Full Name',
                        halign: 'center',
                        width: 200
                    },
                ]
            ],

            onCheck: function(index, row) {
                const exists = $('#dg2').datagrid('getRows').some(r => r.employee_id === row.number);
                if (!exists) {
                    $('#dg2').datagrid('appendRow', {
                        employee_id: row.number,
                        employee_name: row.name
                    });

                    const lastIndex = $('#dg2').datagrid('getRows').length - 1;
                    $('#dg2').datagrid('beginEdit', lastIndex);
                } else {
                    $.messager.alert('Warning', 'Employee already added.', 'warning');
                }
            },

            onUncheck: function(index, row) {
                const rows = $('#dg2').datagrid('getRows');
                const idx = rows.findIndex(r => r.employee_id === row.number);
                if (idx !== -1) {
                    $('#dg2').datagrid('deleteRow', idx);
                }
            },
        }).datagrid('enableFilter');
    })

    function append() {
        let permit_type_id = $("#permit_type_id").combobox('getValue');
        let reason_id = $("#reason_id").combobox('getValue');
        let effective_date = $("#effective_date").datebox('getValue');

        if (permit_type_id != "" && reason_id != "" && effective_date != "") {
            $('#dlg_employee').dialog('open');
        } else {
            toastr.warning("Please select Permit Type, Reason, and Effective Date first.", "Information");
        }
    }

    $(function() {
        var lastIndex;
        $('#dg2').datagrid({
            singleSelect: true,
            fitColumns: true,
            columns: [
                [
                    {
                        field: 'employee_id',
                        hidden: false,
                        width: 200,
                        halign: 'center',
                        title: "Employee ID",
                    },
                    {
                        field: 'employee_name',
                        hidden: false,
                        width: 200,
                        halign: 'center',
                        title: "Full Name",
                    },
                    {
                        field: 'type_balance',
                        width: 120,
                        halign: 'center',
                        title: "Type",
                        formatter: function (value) {
                            if (value === 'adjust') return 'Adjust Balance';
                            if (value === 'add') return 'Add Balance';
                            return value || ''; 
                        },
                        editor: {
                            type: 'combobox',
                            options: {
                                valueField: 'value',
                                textField: 'text',
                                data: [
                                    { value: 'adjust', text: 'Adjust Balance' },
                                    { value: 'add', text: 'Add Balance' },
                                ],
                                editable: false,
                                panelHeight: 'auto',
                                required: true
                            }
                        }
                    }, 
                    {
                        field: 'balance',
                        width: 100,
                        halign: 'center',
                        title: "Input Balance",
                        editor: {
                            type: 'numberbox',
                            options: {
                                required: true
                            }
                        }
                    }, {
                        field: 'start_date',
                        width: 120,
                        halign: 'center',
                        title: "Start Date",
                        editor: {
                            type: 'datebox',
                            options: {
                                required: true,
                                formatter: myformatter,
                                parser: myparser
                            }
                        },
                    }, {
                        field: 'end_date',
                        width: 120,
                        halign: 'center',
                        title: "End Date",
                        editor: {
                            type: 'datebox',
                            options: {
                                formatter: myformatter,
                                parser: myparser
                            }
                        },
                    },
                ]
            ],
            onAfterEdit: function(index, row, changes) {
                $('#dg2').datagrid('beginEdit', index);
            },

            onLoadSuccess: function() {
                const rows = $('#dg2').datagrid('getRows');
                rows.forEach((_, i) => {
                    $('#dg2').datagrid('beginEdit', i);
                });
            }
        });
    })

    // function getRowIndex(target) {
    //     var tr = $(target).closest('tr.datagrid-row');
    //     return parseInt(tr.attr('datagrid-row-index'));
    // }


    function removeRow() {
        var row = $('#dg2').datagrid('getSelected');
        if (row) {
            var index = $('#dg2').datagrid('getRowIndex', row);

            if (row.id) {
                Swal.fire({
                    title: 'Delete Data',
                    text: 'Are you sure you want to delete this data?',
                    icon: 'warning',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        deletedDetailIds.push(row.id);
                        $('#dg2').datagrid('cancelEdit', index);
                        $('#dg2').datagrid('deleteRow', index);
                        lastIndex = undefined;

                        Swal.fire('Deleted!', 'Row has been deleted', 'success');
                    }
                });
            } else {
                $('#dg2').datagrid('cancelEdit', index);
                $('#dg2').datagrid('deleteRow', index);
                lastIndex = undefined;
            }
        } else {
            $.messager.alert("Warning", "Please select the row you want to delete.");
        }
    }

    function endAllEditing() {
        var rows = $('#dg2').datagrid('getRows');
        for (var i = 0; i < rows.length; i++) {
            $('#dg2').datagrid('selectRow', i);
            if (!$('#dg2').datagrid('validateRow', i)) {
                return false;
            }
            $('#dg2').datagrid('endEdit', i);
        }
        return true;
    }
</script>