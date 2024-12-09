<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
    <thead>
        <tr>
            <th rowspan="2" field="ck" checkbox="true"></th>
            <th rowspan="2" data-options="field:'number',width:80,align:'center'">Code</th>
            <th rowspan="2" data-options="field:'name',width:150,halign:'center'">Name</th>
            <th rowspan="2" data-options="field:'type',width:100,halign:'center'">Type</th>
            <th rowspan="2" data-options="field:'ip',width:120,halign:'center'">IP Address</th>
            <th rowspan="2" data-options="field:'port',width:80,halign:'center'">Port</th>
            <th rowspan="2" data-options="field:'keys',width:150,halign:'center'">Comm Keys</th>
            <th rowspan="2" data-options="field:'getData',width:100,halign:'center',formatter:FormatterGetData">Action</th>
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
<div id="dlg_insert" class="easyui-dialog" title="Add New" data-options="closed: true,modal:true" style="width: 400px; padding:10px; top: 20px;">
    <form id="frm_insert" method="post" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Code</span>
                <input style="width:30%;" name="number" required="" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Name</span>
                <input style="width:60%;" name="name" required="" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Type</span>
                <select style="width:60%;" name="type" required="" class="easyui-combobox" panelHeight="auto">
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                    <!-- <option value="BOTH">BOTH</option> -->
                </select>
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">IP Address</span>
                <input style="width:60%;" name="ip" required="" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Port</span>
                <input style="width:30%;" name="port" required="" class="easyui-textbox">
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Keys</span>
                <input style="width:60%;" name="keys" required="" class="easyui-textbox">
            </div>
        </fieldset>
    </form>
</div>

<div id="dlg_upload" class="easyui-dialog" title="Sync Data Machine Attandance" data-options="closed: true,modal:true" style="width: 500px; padding:10px; top: 20px;">
    <form id="frm_upload" method="post" enctype="multipart/form-data" novalidate>
        <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
            <legend><b>Form Data</b></legend>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">IP Address</span>
                <input style="width:40%;" name="ip" id="u_ip" class="easyui-textbox" readonly>
                <input style="width:20%;" name="type" id="u_type" class="easyui-textbox" readonly>
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Port / Key</span>
                <input style="width:30%;" name="port" id="u_port" class="easyui-textbox" readonly>
                <input style="width:30%;" name="keys" id="u_keys" class="easyui-textbox" readonly>
            </div>
            <div class="fitem">
                <span style="width:35%; display:inline-block;">Period</span>
                <input style="width:30%;" name="period_from" id="period_from" value="<?= date("Y-m-d") ?>" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
                <input style="width:30%;" name="period_to" id="period_to" value="<?= date("Y-m-d") ?>" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
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
<iframe id="printout" src="<?= base_url('attandance/machines/print') ?>" style="width: 100%;" hidden></iframe>

<script>
    //ADD DATA
    function add() {
        $('#dlg_insert').dialog('open');
        url_save = '<?= base_url('attandance/machines/create') ?>';
        $('#frm_insert').form('clear');
    }

    //EDIT DATA
    function update() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg_insert').dialog('open');
            $('#frm_insert').form('load', row);
            url_save = '<?= base_url('attandance/machines/update') ?>?id=' + btoa(row.id);
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
                            url: '<?= base_url('attandance/machines/delete') ?>',
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
        window.location.assign('<?= base_url('attandance/machines/print/excel') ?>');
    }
    //RELOAD
    function reload() {
        window.location.reload();
    }

    function FormatterGetData(val, row) {
        var getDataSync = "getDataSync('"+row.ip+"','"+row.port+"','"+row.keys+"','"+row.type+"')";
        return '<a class="button-green" style="width:100%;" onclick="'+getDataSync+'"><i class="fa fa-add"></i> Get Data</a>';
    };

    function getDataSync(ip, port, key, type){
        $("#dlg_upload").dialog('open');
        $("#u_ip").textbox('setValue', ip);
        $("#u_port").textbox('setValue', port);
        $("#u_keys").textbox('setValue', key);
        $("#u_type").textbox('setValue', type);
    }

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
        //SETTING DATAGRID EASYUI
        $('#dg').datagrid({
            url: '<?= base_url('attandance/machines/datatables') ?>',
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
                text: 'Sync Data',
                iconCls: 'icon-ok',
                handler: function() {
                    $('#frm_upload').form('submit', {
                        url: '<?= base_url('attandance/machines/upload') ?>',
                        onSubmit: function() {
                            if ($(this).form('validate') == false) {
                                return $(this).form('validate');
                            } else {
                                $.messager.progress({
                                    title: 'Please Wait',
                                    msg: 'Get Data Machine'
                                });
                            }
                        },
                        success: function(result) {
                            $.messager.progress('close');
                            var json = eval('(' + result + ')');
                            
                            if(json.code == 200){
                                toastr.success(json.message);
                                requestData(json.total, json.results);
                                function requestData(total, json, number = 1, value = 0, success = 1, failed = 1) {
                                    if (value < 100) {
                                        value = Math.floor((number / total) * 100);
                                        $('#p_upload').progressbar('setValue', value);
                                        $('#p_start').html(number);
                                        $('#p_finish').html(total);

                                        $.ajax({
                                            type: "POST",
                                            async: true,
                                            url: "<?= base_url('attandance/machines/uploadCreate') ?>",
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
                            }else{
                                toastr.error(json.message);
                            }
                        }
                    });
                }
            }]
        });
    });
</script>