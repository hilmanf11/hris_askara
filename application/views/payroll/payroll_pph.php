<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
    <thead>
        <tr>
            <th rowspan="2" data-options="field:'division_name',width:200,halign:'center'">Division</th>
            <th rowspan="2" data-options="field:'departement_name',width:200,halign:'center'">Departement</th>
            <th rowspan="2" data-options="field:'departement_sub_name',width:200,halign:'center'">Departement Sub</th>
            <th rowspan="2" data-options="field:'employee_name',width:200,halign:'center'">Employee</th>
            <th rowspan="2" data-options="field:'date_sign',width:100,halign:'center'">Join Date</th>
            <th colspan="12" data-options="halign:'center'">
                    <span id="years-title">[YEAR]</span>
            </th>
        </tr>
        <tr>
            <th data-options="field:'jan',width:80,align:'right',formatter:numberformat">Jan</th>
            <th data-options="field:'feb',width:80,align:'right',formatter:numberformat">Feb</th>
            <th data-options="field:'mar',width:80,align:'right',formatter:numberformat">Mar</th>
            <th data-options="field:'apr',width:80,align:'right',formatter:numberformat">Apr</th>
            <th data-options="field:'may',width:80,align:'right',formatter:numberformat">May</th>
            <th data-options="field:'jun',width:80,align:'right',formatter:numberformat">Jun</th>
            <th data-options="field:'jul',width:80,align:'right',formatter:numberformat">Jul</th>
            <th data-options="field:'aug',width:80,align:'right',formatter:numberformat">Aug</th>
            <th data-options="field:'sep',width:80,align:'right',formatter:numberformat">Sep</th>
            <th data-options="field:'oct',width:80,align:'right',formatter:numberformat">Oct</th>
            <th data-options="field:'nov',width:80,align:'right',formatter:numberformat">Nov</th>
            <th data-options="field:'dec',width:80,align:'right',formatter:numberformat">Dec</th>
        </tr>
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
                        <span style="width:30%; display:inline-block;">Period</span>
                        <input style="width:23%;" id="filter_month_start" value="<?= date("m") ?>" class="easyui-combobox" data-options="prompt:'Month'">
                        To
                        <input style="width:23%;" id="filter_month_end" value="<?= date("m") ?>" class="easyui-combobox" data-options="prompt:'Month'">
                        <input style="width:11%;" id="filter_year" value="<?= date("Y") ?>" class="easyui-combobox" data-options="prompt:'Year'">
                    </div>
                    <div class="fitem">
                        <span style="width:30%; display:inline-block;">Division</span>
                        <input style="width:60%;" id="filter_division" class="easyui-combobox">
                    </div>
                    <div class="fitem">
                        <span style="width:30%; display:inline-block;">Departement</span>
                        <input style="width:60%;" id="filter_departement" class="easyui-combobox">
                    </div>
                    <div class="fitem">
                        <span style="width:30%; display:inline-block;"></span>
                        <a href="javascript:;" class="easyui-linkbutton" onclick="filter()"><i class="fa fa-search"></i> Filter Data</a>
                    </div>
                </div>
                <div style="width: 50%; float: left;">
                    <div class="fitem">
                        <span style="width:30%; display:inline-block;">Departement Sub</span>
                        <input style="width:60%;" id="filter_departement_sub" class="easyui-combobox">
                    </div>
                    <div class="fitem">
                        <span style="width:30%; display:inline-block;">Employee</span>
                        <input style="width:60%;" id="filter_employee" class="easyui-combogrid">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <?= $button ?>
</div>

<!-- DIALOG SAVE AND UPDATE -->
<div id="dlg_insert" class="easyui-dialog" title="Add New" data-options="closed: true,modal:true" style="width: 700px; padding:10px; top: 20px;">
    <form id="frm_insert" method="post" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Periode</span>
                <input style="width:28%;" name="period_start" id="period_start" class="easyui-combogrid"> To
                <input style="width:28%;" name="period_end" id="period_end" data-options="prompt:'Date To'" readonly class="easyui-textbox">
            </div>
            <div class="fitem" style="margin-bottom: 10px;">
                <span style="width:35%; display:inline-block;">Employee ID</span>
                <input style="width:60%;" name="employee_id" id="employee_id" required="" class="easyui-combogrid">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">PPH 21</span>
                <input style="width:60%;" name="pph" id="pph" required="" class="easyui-numberbox">
            </div>
        </fieldset>
    </form>
</div>

<div id="dlg_upload" class="easyui-dialog" title="Upload Data" data-options="closed: true,modal:true" style="width: 500px; padding:10px; top: 20px;">
    <form id="frm_upload" method="post" enctype="multipart/form-data" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:30%; display:inline-block;">File Upload</span>
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

<!-- PDF -->
<iframe id="printout" src="<?= base_url('payroll/payroll_pph/print') ?>" style="width: 100%;" hidden></iframe>

<script>
    var monthFields = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
    //ADD DATA
    function add() {
        $('#dlg_insert').dialog('open');
        url_save = '<?= base_url('payroll/payroll_pph/create') ?>';
        $('#frm_insert').form('clear');
        $('#employee_id').combogrid('enable');
    }

    //UPLOAD DATA
    function upload() {
        $('#dlg_upload').dialog('open');
    }

    function download_excel() {
        window.location.assign('<?= base_url('template/tmp_payroll_pph.xls') ?>');
    }

    function filter() {
        var mStart = parseInt($('#filter_month_start').combobox('getValue'), 10);
        var mEnd   = parseInt($('#filter_month_end').combobox('getValue'), 10);
        var year   = $('#filter_year').combobox('getValue');

        var filter_division = $("#filter_division").combobox('getValue');
        var filter_departement = $("#filter_departement").combobox('getValue');
        var filter_departement_sub = $("#filter_departement_sub").combobox('getValue');
        var filter_employee = $("#filter_employee").combobox('getValue');

        var url = "?month_start=" + mStart +
            "&month_end=" + mEnd +
            "&year=" + year +
            "&filter_division=" + filter_division +
            "&filter_departement=" + filter_departement +
            "&filter_departement_sub=" + filter_departement_sub +
            "&filter_employee=" + filter_employee;
            
        currentYear = year;

        $.each(monthFields, function(i, field){
            $('#dg').datagrid('hideColumn', field);
        });

        for (var m = mStart; m <= mEnd; m++) {
            $('#dg').datagrid('showColumn', monthFields[m-1]);
        }

        $('#dg').datagrid('load', {
            month_start: mStart,
            month_end:   mEnd,
            year:        year,
            filter_division:        filter_division,
            filter_departement:     filter_departement,
            filter_departement_sub: filter_departement_sub,
            filter_employee:        filter_employee
        });

        $("#printout").contents().find('html').html("<center><br><br><br><b style='font-size:20px;'>Please Wait...</b></center>");
        $("#printout").attr('src', '<?= base_url('payroll/payroll_pph/print') ?>' + url);
    }

    //PRINT PDF
    function pdf() {
        $("#printout").get(0).contentWindow.print();
    }
    //PRINT EXCEL
    function excel() {
        var mStart = parseInt($('#filter_month_start').combobox('getValue'), 10);
        var mEnd   = parseInt($('#filter_month_end').combobox('getValue'), 10);
        var year   = $('#filter_year').combobox('getValue');

        var filter_division = $("#filter_division").combobox('getValue');
        var filter_departement = $("#filter_departement").combobox('getValue');
        var filter_departement_sub = $("#filter_departement_sub").combobox('getValue');
        var filter_employee = $("#filter_employee").combobox('getValue');

        var url = "?month_start=" + mStart +
            "&month_end=" + mEnd +
            "&year=" + year +
            "&filter_division=" + filter_division +
            "&filter_departement=" + filter_departement +
            "&filter_departement_sub=" + filter_departement_sub +
            "&filter_employee=" + filter_employee;

        window.location.assign('<?= base_url('payroll/payroll_pph/print/excel') ?>' + url);
    }
    //RELOAD
    function reload() {
        window.location.reload();
    }

    var currentYear = null;
    $(function() {
        $('#dg').datagrid({
            url: '<?= base_url('payroll/payroll_pph/datatables') ?>',
            pagination: true,
            rownumbers: true,
            fit: true,
            pageList: [20, 50, 100, 500, 1000],
            pageSize: 20,
            onLoadSuccess: function() {
                if (!currentYear) return;

                console.log(currentYear);
                

                var panel = $('#dg').datagrid('getPanel');
                panel.find('#years-title').text(currentYear);
            }
        });

        filter();

        //SAVE DATA
        $('#dlg_insert').dialog({
            buttons: [{
                text: 'Save',
                iconCls: 'icon-ok',
                handler: function() {
                    $('#frm_insert').form('submit', {
                        url: url_save,
                        onSubmit: function() {
                            return $(this).form('validate');
                        },
                        success: function(result) {
                            var result = eval('(' + result + ')');

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
            }]
        });

        //UPLOAD DATA
        $('#dlg_upload').dialog({
            buttons: [{
                text: 'List Failed',
                handler: function() {
                    window.open('<?= base_url('payroll/payroll_pph/uploadDownloadFailed') ?>', '_blank');
                }
            }, {
                text: 'Upload',
                iconCls: 'icon-ok',
                handler: function() {
                    $('#frm_upload').form('submit', {
                        url: '<?= base_url('payroll/payroll_pph/upload') ?>',
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
                                url: "<?= base_url('payroll/payroll_pph/uploadclearFailed') ?>"
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
                                        url: "<?= base_url('payroll/payroll_pph/uploadCreate') ?>",
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
                                                    url: "<?= base_url('payroll/payroll_pph/uploadcreateFailed') ?>",
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
                                            if (textStatus == "error") {
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
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            }]
        });

        //Get Departement
        $('#filter_division').combobox({
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
                $('#filter_departement').combobox({
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
                        $('#filter_departement_sub').combobox({
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
                            onSelect: function(departement_sub) {
                                $('#filter_employee').combogrid({
                                    url: '<?= base_url('employee/employees/reads?departement_sub_id=') ?>' + departement_sub.id,
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
                                            width: 120
                                        }, {
                                            field: 'name',
                                            title: 'Employee Name',
                                            width: 200
                                        }]
                                    ],
                                });
                            }
                        });
                    }
                });
            }
        });

        $('#filter_employee').combogrid({
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
                    width: 120
                }, {
                    field: 'name',
                    title: 'Employee Name',
                    width: 200
                }]
            ],
        });

        $('#employee_id').combogrid({
            url: '<?= base_url('employee/employees/readEmployeeNotPkl') ?>',
            panelWidth: 450,
            idField: 'id',
            textField: 'name',
            mode: 'remote',
            fitColumns: true,
            prompt: 'Choose Employee',
            columns: [
                [{
                    field: 'number',
                    title: 'Employee ID',
                    width: 120
                }, {
                    field: 'name',
                    title: 'Employee Name',
                    width: 200
                }]
            ]
        });

        $('#period_start').combogrid({
            url: '<?= base_url('payroll/cutoff/reads') ?>',
            panelWidth: 300,
            idField: 'start',
            textField: 'start',
            mode: 'remote',
            fitColumns: true,
            prompt: 'Date From',
            columns: [
                [{
                    field: 'start',
                    title: 'Date From',
                    width: 120
                }, {
                    field: 'finish',
                    title: 'Date To',
                    width: 120
                }]
            ],
            onSelect: function(val, row) {
                $("#period_end").textbox('setValue', row.finish);
            }
        });

        $('#filter_month_start').combobox({
            url: '<?php echo base_url('report/payroll_account_cc/readMonths'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Month',
        });

        $('#filter_month_end').combobox({
            url: '<?php echo base_url('report/payroll_account_cc/readMonths'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Month',
        });

        $('#filter_year').combobox({
            url: '<?php echo base_url('report/payroll_account_cc/readYears'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Year',
        });
    });

    //CELLSTYLE STATUS
    function statusStyler(value, row, index) {
        if (value == "" || value == null) {
            return 'background: #53D636; color:white;';
        } else {
            return 'background: #FF5F5F; color:white;';
        }
    }
    //FORMATTER STATUS
    function statusFormatter(value) {
        if (value == "" || value == null) {
            return 'Approved';
        } else {
            return 'Checked';
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

    function numberformat(value, row) {
        if (value != null) {
            const formatter = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            });
            return "<b>" + formatter.format(value) + "</b>";
        }
    }
</script>