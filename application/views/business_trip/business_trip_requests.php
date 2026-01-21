<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
    <thead>
        <tr>
            <th rowspan="2" field="ck" checkbox="true"></th>
            <th rowspan="2" data-options="field:'request_date',width:120,halign:'center',formatter:FormatterDate">Request Date</th>
            <th rowspan="2" data-options="field:'request_no',width:200">Request No</th>
            <th rowspan="2" data-options="field:'division_name',width:200,halign:'center'">Division</th>
            <th rowspan="2" data-options="field:'departement_name',width:150,halign:'center'">Departement</th>
            <th rowspan="2" data-options="field:'departement_sub_name',width:150,halign:'center'">Departement Sub</th>
            <th rowspan="2" data-options="field:'employee_name',width:150,halign:'center'">Name</th>
            <th rowspan="2" data-options="field:'date_in',width:150,halign:'center',formatter:FormatterDate">Est Berangkat Dinas</th>
            <th rowspan="2" data-options="field:'date_out',width:150,halign:'center',formatter:FormatterDate">Est Pulang Dinas</th>
            <th rowspan="2" data-options="field:'trip_type',width:100,halign:'center'">Tipe Trip</th>
            <th rowspan="2" data-options="field:'area',width:120,halign:'center'">Tujuan</th>
            <th rowspan="2" data-options="field:'approved',width:100,align:'center',styler:statusStyler, formatter:statusFormatter">Approval</th>
            <th rowspan="2" data-options="field:'approved_by',width:120,align:'center'">Approval By</th>
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
<div id="toolbar">
    <div class="easyui-accordion" style="width:100%; margin-bottom:5px;">
        <div title="Click this to hide the filter" data-options="onCollapse: function(){ $('#dg').datagrid('reload'); }" style="padding:10px; background:#F4F4F4;">
            <fieldset style="width: 100%; border:2px solid #d0d0d0; border-radius:4px;">
                <legend><b>Form Filter Data</b></legend>
                <div style="width: 50%; float: left;">
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Division</span>
                        <input style="width:60%;" id="filter_divisions" class="easyui-combogrid">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Departement</span>
                        <input style="width:60%;" id="filter_departements" class="easyui-combogrid">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Departement Sub</span>
                        <input style="width:60%;" id="filter_departement_subs" class="easyui-combogrid">
                    </div>
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;"></span>
                        <a href="javascript:;" class="easyui-linkbutton" onclick="filter()"><i class="fa fa-search"></i> Filter Data</a>
                    </div>
                </div>
                <div style="width: 50%; float: left;">
                    <div class="fitem">
                        <span style="width:35%; display:inline-block;">Employee Name</span>
                        <input style="width:60%;" id="filter_employees" class="easyui-combogrid">
                    </div>
                    <!-- <div class="fitem">
                        <span style="width:35%; display:inline-block;">Status</span>
                        <select style="width:60%;" id="filter_status" class="easyui-combobox" data-options="panelHeight:'auto'">
                            <option value="">Choose All</option>
                            <option value="0">Active</option>
                            <option value="1">Not Active</option>
                        </select>
                    </div> -->
            </fieldset>
        </div>
    </div>
    <?= $button ?>
</div>

<!-- PDF -->
<iframe id="printout" src="<?= base_url('business_trip/business_trip_requests/print') ?>" style="width: 100%;" hidden></iframe>

<script>
    //ADD DATA
    function add() {
          window.location.href = "<?= base_url('business_trip/business_trip_requests/form_requests/') ?>" + '<?= $id_menu ?>';
    }

    //EDIT DATA
    function update() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            console.log(row);
            
            window.location.href = "<?= base_url('business_trip/business_trip_requests/form_requests/') ?>" + '<?= $id_menu ?>' + '/' + row.id;
            
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
                        if (row.status != 0) {
                            $.ajax({
                                method: 'post',
                                url: '<?= base_url('business_trip/business_trip_requests/delete') ?>',
                                data: {
                                    id: row.dinas_emp_id
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
                        } else {
                            toastr.error("You can't delete this data because the status is active!");
                        }
                    }
                }
            });
        } else {
            toastr.warning("Please select one of the data in the table first!", "Information");
        }
    }
    //FILTER DATA
    function filter() {
        var filter_divisions = $("#filter_divisions").combobox('getValue');
        var filter_departements = $("#filter_departements").combobox('getValue');
        var filter_departement_subs = $("#filter_departement_subs").combobox('getValue');
        var filter_employees = $("#filter_employees").combogrid('getValue');
        // var filter_status = $("#filter_status").combobox('getValue');

        var url = "?filter_divisions=" + filter_divisions +
            "&filter_departements=" + filter_departements +
            "&filter_departement_subs=" + filter_departement_subs +
            "&filter_employees=" + filter_employees
            // + "&filter_status=" + filter_status
            
            ;

        $('#dg').datagrid({
            url: '<?= base_url('business_trip/business_trip_requests/datatables') ?>' + url
        });

        $("#printout").contents().find('html').html("<center><br><br><br><b style='font-size:20px;'>Please Wait...</b></center>");
        $("#printout").attr('src', '<?= base_url('business_trip/business_trip_requests/print') ?>' + url);
    }

    //PRINT PDF
    function pdf() {
        alert('on development')
        // $("#printout").get(0).contentWindow.print();
    }
    //PRINT EXCEL
    function excel() {
        alert('on development')
    //     var filter_divisions = $("#filter_divisions").combobox('getValue');
    //     var filter_departements = $("#filter_departements").combobox('getValue');
    //     var filter_departement_subs = $("#filter_departement_subs").combobox('getValue');
    //     var filter_employees = $("#filter_employees").combogrid('getValue');
    //     var filter_status = $("#filter_status").combobox('getValue');

    //     var url = "?filter_divisions=" + filter_divisions +
    //         "&filter_departements=" + filter_departements +
    //         "&filter_departement_subs=" + filter_departement_subs +
    //         "&filter_employees=" + filter_employees 
    //         // + "&filter_status=" + filter_status
    //         ;

    //     window.location.assign('<?= base_url('business_trip/business_trip_requests/print/excel') ?>' + url);
    }
    //RELOAD
    function reload() {
        window.location.reload();
    }

    $(function() {
        //SETTING DATAGRID EASYUI
        $('#dg').datagrid({
            url: '<?= base_url('business_trip/business_trip_requests/datatables') ?>',
            pagination: true,
            rownumbers: true,
            fit: true,
            pageList: [20, 50, 100, 500, 1000],
            pageSize: 20,
            rowStyler: function(index, row) {
                if (row.status == 1) {
                    return 'background-color:#FFDCDC;';
                }
            }
        });

        //Get Division
        $('#division_id').combogrid({
            url: '<?= base_url('employee/divisions/reads') ?>',
            panelWidth: 420,
            idField: 'id',
            textField: 'name',
            mode: 'remote',
            fitColumns: true,
            prompt: "Choose Division",
            columns: [
                [{
                    field: 'number',
                    title: 'Division No',
                    width: 80
                }, {
                    field: 'name',
                    title: 'Division Name',
                    width: 250
                }, ]
            ]
        });

        //Get Division
        $('#filter_divisions').combobox({
            url: '<?php echo base_url('employee/divisions/reads'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Choose All',
            icons: [{
                iconCls: 'icon-clear',
                handler: function(e) {
                    $(e.data.target).combobox('clear').combobox('textbox').focus();
                }
            }],
            onSelect: function(division) {
                $('#filter_departements').combobox({
                    url: '<?php echo base_url('employee/departements/reads'); ?>?division_id=' + division.id,
                    valueField: 'id',
                    textField: 'name',
                    prompt: 'Choose All',
                    icons: [{
                        iconCls: 'icon-clear',
                        handler: function(e) {
                            $(e.data.target).combobox('clear').combobox('textbox').focus();
                        }
                    }],
                    onSelect: function(departement) {
                        $('#filter_departement_subs').combobox({
                            url: '<?php echo base_url('employee/departement_subs/reads'); ?>?division_id=' + division.id + '&departement_id=' + departement.id,
                            valueField: 'id',
                            textField: 'name',
                            prompt: 'Choose All',
                            icons: [{
                                iconCls: 'icon-clear',
                                handler: function(e) {
                                    $(e.data.target).combobox('clear').combobox('textbox').focus();
                                }
                            }],
                        });
                    }
                });
            }
        });

        $('#filter_employees').combogrid({
            url: '<?= base_url('employee/employees/reads') ?>',
            panelWidth: 450,
            idField: 'id',
            textField: 'name',
            mode: 'remote',
            fitColumns: true,
            prompt: 'Choose All',
            icons: [{
                iconCls: 'icon-clear',
                handler: function(e) {
                    $(e.data.target).combogrid('clear').combogrid('textbox').focus();
                }
            }],
            columns: [
                [{
                    field: 'number',
                    title: 'Employee ID',
                    width: 150
                }, {
                    field: 'name',
                    title: 'Employee Name',
                    width: 200
                }]
            ],
        });
    });

    function FormatterDate(val) {
        if (val == "0000-00-00") {
            return '-';
        } else {
            return val;
        }
    };

    //CELLSTYLE STATUS
    function statusStyler(value, row, index) {
        if (row.approved_to == "" || row.approved_to == null) {
            return 'background: #53D636; color:white;';
        } else {
            return 'background: #FF5F5F; color:white;';
        }
    }
    //FORMATTER STATUS
    function statusFormatter(value, row) {
        if (row.approved_to == "" || row.approved_to == null) {
            return 'Approved';
        } else {
            return 'Checked';
        }
    };

    function fileFormatter(value) {
        if (value == "" || value == null) {
            return '-';
        } else {
            return '<a href="' + value + '" style="text-decoration: none;"><i class="fa fa-download"></i> Download</a>';
        }
    };

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
</script>