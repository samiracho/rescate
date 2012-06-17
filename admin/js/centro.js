// formulario para editar centros
Ext.define('RESCATE.form.editarCentro', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarCentro',
    height: 420,
    width: 500,
    layout: {
        type: 'fit'
    },
    title: t('Center'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var tiposCentros = Ext.create('widget.store.TiposCentros', {
        });
        me.items = [
        {
            xtype: 'form',
            bodyPadding: 6,
            layout: {
                align: 'stretch',
                type: 'vbox'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '94%'
            },
            title: '',
            items: [
            {
                xtype: 'container',
                height: 120,
                layout: {
                    type: 'column'
                },
                items: [
                {
                    xtype: 'container',
                    layout: {
                        type: 'anchor'
                    },
                    columnWidth: 0.5,
                    items: [
                    {
                        xtype: 'hiddenfield',
                        name: 'centro_id',
                        itemId: 'campoIdCentro'
                    }, {
                        xtype: 'textfield',
                        name: 'centro_nombre',
                        fieldLabel: t('Name') + '*',
                        allowBlank: false
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'nombre',
                        name: 'centro_tipo',
                        store: tiposCentros,
                        fieldLabel: t('Type') + '*',
                        emptyText: t('Type')
                    }]
                }, {
                    xtype: 'container',
                    layout: {
                        type: 'anchor'
                    },
                    columnWidth: 0.5,
                    items: [
                    {
                        xtype: 'textfield',
                        name: 'centro_codigo',
                        fieldLabel: t('Code') + '*',
                        allowBlank: false,
                        listeners: {
                            scope: me,
                            blur: function (field, options)
                            {
                                me.comprobarCodUnico(field, options)
                            }
                        }
                    }]
                }]
            }, {
                xtype: CONFIG.textArea,
                flex: 1,
                name: 'centro_detalles',
                fieldLabel: t('Details'),
                anchor: '100% -5'
            }],
            dockedItems: me.barraBotones()
        }];
        me.addListener('datoscargados', function (component)
        {
            tiposCentros.load();
        });
        me.callParent(arguments);
    },
    comprobarCodUnico: function (field, options)
    {
        var ruta = 'datos/centro.php';
        var idCentro = field.up('form').down('#campoIdCentro').getValue();
        var parametros =
        {
            'action': 'checkCodUnique',
            'centro_codigo': field.getValue(),
            'idCentro': idCentro
        };
        this.comprobarUnico(field, options, parametros, ruta);
    }
});
// grid con la lista editable de centros con seleccion
Ext.define('RESCATE.grid.CentroSeleccionar', {
    alias: 'widget.grid.CentroSeleccionar',
    extend: 'RESCATE.grid.grid',
    itemId: 'gridCentroSeleccionar',
    title: t('Center') + '*',
    soloIconos: true,
    // itemId del formulario desde el que se selecciona el centro
    nameRelacionCentro: '',
    formulario: 'editarCentro',
    mensajeAgregar: t('Add College'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'centro_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'centro_nombre',
        sortable: true
    }, {
        text: t('Type'),
        flex: 1,
        dataIndex: 'centro_tipo',
        sortable: true,
        hidden: false
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'centro_detalles',
        sortable: true,
        hidden: true
    }, {
        text: t('Code'),
        width: 160,
        dataIndex: 'centro_codigo',
        sortable: true,
        hidden: true
    }],
    viewConfig: {
        listeners: {
            itemclick: function (view, rec, node, index, e)
            {
                var formulario = view.up('form');
                var campoIdCentro = formulario.down('hiddenfield[name=' + view.ownerCt.nameRelacionCentro + ']');
                campoIdCentro.setValue(rec.data['centro_id']);
            }
        }
    },
    initComponent: function ()
    {
        var me = this;
        me.store = Ext.create('widget.store.Centro', {
        });
        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            mode: 'SINGLE'
        });
        // cuando cargue si centro_id no es nulo, lo seleccionamos en la lista
        me.getStore().on('load', function (store)
        {
            var formulario = me.up('form');
            var valorIdCentro = formulario.down('hiddenfield[name=' + me.nameRelacionCentro + ']').getValue();
            var totalRecords = store.getTotalCount();
            if (valorIdCentro != "")
            {
                var recordIndex = store.findExact('centro_id', valorIdCentro);
                if (recordIndex != -1) me.getSelectionModel().select(recordIndex);
            }
        });
        // cuando cargue si obra_id no es nulo, lo seleccionamos en la lista y la ponemos la primera
        me.getStore().on('beforeload', function (store)
        {
            var formulario = me.up('form');
            var valorId = formulario.down('hiddenfield[name=' + me.nameRelacionCentro + ']').getValue();
            me.store.getProxy().extraParams.idCentro = valorId;
        });
        me.callParent(arguments);
    }
});