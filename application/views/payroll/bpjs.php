<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
    <thead>
        <tr>
            <th rowspan="2" field="ck" checkbox="true"></th>
            <th rowspan="2" data-options="field:'number',width:100,align:'center'">Code</th>
            <th rowspan="2" data-options="field:'name',width:200,halign:'center'">Name</th>
            <th colspan="2" data-options="field:'',width:100,halign:'center'"> Created</th>
            <th colspan="2" data-options="field:'',width:100,halign:'center'"> Updated</th>
        </tr>
        <tr>
            <th data-options="field:'created_by',width:100,align:'center'"> By</th>
            <th data-options="field:'created_date',width:150,align:'center'"> Date</th>
            <th data-options="field:'updated_by',width:100,align:'center'"> By</th>
            <th data-options="field:'updated_date',width:150,align:'center'"> Date</th>
        </tr>
    </thead>
</table>

<!-- TOOLBAR DATAGRID -->
<div id="toolbar" style="height: 35px;">
    <?= $button ?>
</div>

<!-- DIALOG SAVE AND UPDATE -->
<div id="dlg_insert" class="easyui-dialog" title="Add New" data-options="closed: true,modal:true" style="width: 980px; padding:10px; top: 20px;">
    <form id="frm_insert" method="post" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Code</span>
                <input style="width:30%;" name="number" id="number_bpjs" required="" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Name</span>
                <input style="width:30%;" name="name" id="name_bpjs" required="" class="easyui-textbox">
            </div>

        </fieldset>
        <table id="dg2" class="easyui-datagrid" style="width:100%; height: 310px;" title="Setting" toolbar="#toolbar2"></table>
    </form>
</div>

<div id="toolbar2">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="append()"><i class="fa fa-plus"></i> Add</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="removeit()"><i class="fa fa-times"></i> Remove</a>
</div>

<!-- PDF -->
<iframe id="printout" src="<?= base_url('payroll/bpjs/print') ?>" style="width: 100%;" hidden></iframe>

<script>
    let dg2_editIndex = undefined;
    //ADD DATA
    function add() {
        $('#dlg_insert').dialog('open');
        $('#dg2').datagrid('loadData', []);
        url_save = '<?= base_url('payroll/bpjs/create') ?>';
        $('#frm_insert').form('clear');
    }

    //EDIT DATA
    function update() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg_insert').dialog('open');
            $('#frm_insert').form('load', row);
            url_save = '<?= base_url('payroll/bpjs/update') ?>';
            addTable('<?= base_url('payroll/bpjs/datatableUpdates?number=') ?>' + window.btoa(row.number));
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
                            url: '<?= base_url('payroll/bpjs/delete') ?>',
                            data: {
                                id: row.id
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
    //PRINT PDF
    function pdf() {
        $("#printout").get(0).contentWindow.print();
    }
    //PRINT EXCEL
    function excel() {
        window.location.assign('<?= base_url('payroll/bpjs/print/excel') ?>');
    }
    //RELOAD
    function reload() {
        window.location.reload();
    }

    $(function() {
        addTable();
        //SETTING DATAGRID EASYUI
        $('#dg').datagrid({
            url: '<?= base_url('payroll/bpjs/datatables') ?>',
            pagination: true,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: true,
            fit: true,
            pageList: [20, 50, 100, 500, 1000],
            pageSize: 20,
        }).datagrid('enableFilter');

        //SAVE DATA
        $('#dlg_insert').dialog({
            buttons: [{
                text: 'Save',
                iconCls: 'icon-ok',
                handler: function() {
                    let code = $("#number_bpjs").textbox('getValue');
                    
                    let name = $("#name_bpjs").textbox('getValue');
                    if (code == "" || name == "") {
                        toastr.error("Please fill in all the required fields");
                        return;
                    }

                    $("#dg2").datagrid('acceptChanges');
                    var rows = $('#dg2').datagrid('getRows');
                    var totalrows = rows.length;
                    endEditing();
                    let successCount = 0;
                    let totalRequests = totalrows;                    

                    for (let i = 0; i < totalrows; i++) {
                        console.log(rows);
                        
                        $.ajax({
                            type: "post",
                            url: url_save,
                            data: {
                                number: code,
                                name: name,
                                division_id: rows[i].division_id,
                                company: rows[i].company,
                                employee: rows[i].employee,
                                id:  window.btoa(rows[i].id),
                            },
                            dataType: "json",
                                success: function(result) {
                                if (result.theme == "success") {
                                    toastr.success(result.message, result.title);
                                } else {
                                    toastr.error(result.message, result.title);
                                }

                                $('#dlg_insert').dialog('close');
                                $('#dg').datagrid('reload');
                            }
                        });
                    }
                }
            }]
        });
    });

    function addTable(link = "") {
        $('#dg2').datagrid({
            url: link,
            singleSelect: true,
            fitColumns: true,
            rownumbers: true,
            toolbar: '#toolbar2',
            nowrap: false,
            columns: [
                [
                    {
                        title: 'id',
                        field: 'id',
                        hidden: true,
                        rowspan: 2,
                    },
                    { 
                        title: 'Company', 
                        field: 'division_id', 
                        rowspan: 2,
                        width: 250,
                        formatter: function(value, row) {
                            return row.division_name;
                        },
                        editor: {
                            type: 'combobox',
                            options: {
                                url: '<?php echo base_url('employee/divisions/reads'); ?>',
                                valueField: 'id',
                                textField: 'name',
                                panelHeight: 'auto',
                                required: true,
                            }
                        }
                    },
                    { 
                        title: 'Payment Composition', 
                        colspan: 2, 
                        align: 'center' 
                    },
                    { 
                        title: 'Created', 
                        colspan: 2, 
                        align: 'center' 
                    },
                    { 
                        title: 'Updated', 
                        colspan: 2, 
                        align: 'center' 
                    }
                ],
                [
                    {
                        field: 'company', 
                        title: '% Company', 
                        width: 100, 
                        align: 'center',
                        editor: { 
                            type: 'numberbox', 
                            options: 
                                { 
                                    buttonText: '%',
                                    precision: 2, 
                                    min: 0, 
                                    max: 100, 
                                    required: true 
                                } 
                        }
                    },
                    { 
                        field: 'employee', 
                        title: '% Employee', 
                        width: 100, 
                        align: 'center',
                        editor: { 
                            type: 'numberbox', 
                            options: 
                            { 
                                buttonText: '%',
                                precision: 2, 
                                min: 0, 
                                max: 100, 
                                required: true 
                            } 
                        }
                    },
                    { 
                        field: 'created_by', 
                        title: 'By', 
                        width: 100, 
                        align: 'center'
                    },
                    { 
                        field: 'created_date', 
                        title: 'Date', 
                        width: 150, 
                        align: 'center'
                    },
                    { 
                        field: 'updated_by', 
                        title: 'By', 
                        width: 100, 
                        align: 'center'
                    },
                    { 
                        field: 'updated_date', 
                        title: 'Date', 
                        width: 150, 
                        align: 'center'
                    }
                ]
            ],
            onClickCell: onClickCell
        });
    }

    function append() {
        let number_bpjs = $("#number_bpjs").textbox('getValue');
        let name_bpjs = $("#name_bpjs").textbox('getValue');
        if (number_bpjs != "" && name_bpjs != "") {
                var firstRowIndex = $('#dg2').datagrid('getRows').length > 0 ? 0 : undefined;
                $('#dg2').datagrid('insertRow', {
                    index: firstRowIndex,
                    row: {
                        'id': 0
                    }
                });
                $('#dg2').datagrid('selectRow', firstRowIndex).datagrid('beginEdit', firstRowIndex);
        } else {
            toastr.error("Please Choose Supplier & Category first");
        }
    }

    function onClickCell(index, field) {
        if (dg2_editIndex != index) {
            $('#dg2').datagrid('selectRow', index).datagrid('beginEdit', index);
            dg2_editIndex = index;
        }
    }

    function removeit() {
        if (dg2_editIndex == undefined) {
            return true;
        }

        var dg = $('#dg2');
        var row = dg.datagrid('getSelected');
        
        var rowIndex = dg.datagrid('getRowIndex', row);

        if (row.id != 0) {
            $.messager.confirm('Warning', 'Are you sure you want to delete this data?', function(r) {
                if (r) {
                    $.ajax({
                        method: 'post',
                        url: '<?= base_url('payroll/bpjs/delete') ?>',
                        data: {
                            id: row.id
                        },
                        success: function(result) {
                            var result = eval('(' + result + ')');
                            toastr.success(result.message);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR.statusText);
                            $.messager.alert("Error", jqXHR.statusText, 'error');
                        },
                        complete: function(data) {
                            dg.datagrid('reload');
                        }
                    });
                }
            });
        }
        $('#dg2').datagrid('cancelEdit', dg2_editIndex).datagrid('deleteRow', dg2_editIndex);
        dg2_editIndex = undefined;
    }

    function endEditing() {
        if (dg2_editIndex == undefined) {
            return true
        }
        if ($('#dg2').datagrid('validateRow', dg2_editIndex)) {
            $('#dg2').datagrid('endEdit', dg2_editIndex);
            dg2_editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
</script>