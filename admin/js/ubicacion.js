// formulario para editar ubicacion-obra
Ext.define('RESCATE.form.editarUbicacionObra', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarUbicacionObra',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Location-Work Of Art'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'textfield',
                name: 'ubicacion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'ubicacionobra_obra_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'ubicacionobra_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar formaciones
Ext.define('RESCATE.form.editarUbicacion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarUbicacion',
    itemId: 'editarUbicacion',
    height: 490,
    width: 695,
    // itemId del grid al que está ligado este formulario
    layout: {
        type: 'fit'
    },
    title: t('Position'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var paisStore = Ext.create('widget.store.Pais', {
        });
        var provinciaStore = Ext.create('widget.store.Provincia', {
        });
        paisStore.load();
        provinciaStore.load();
        me.items = [
        {
            xtype: 'form',
            layout: {
                type: 'fit'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '94%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                plain: true,
                flex: 1,
                items: [
                {
                    xtype: 'panel',
                    bodyPadding: 6,
                    layout: {
                        align: 'stretch',
                        type: 'vbox'
                    },
                    title: t('Details') + '*',
                    items: [
                    {
                        xtype: 'textfield',
                        name: 'ubicacion_nombre',
                        fieldLabel: t('Name') + '*',
                        allowBlank: false
                    }, {
                        xtype: 'hiddenfield',
                        name: 'ubicacion_id'
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'ubicacion_detalles',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'panelDireccion',
                    paisName: 'ubicacion_pais',
                    provinciaName: 'ubicacion_provincia',
                    poblacionName: 'ubicacion_poblacion',
                    direccionName: 'ubicacion_direccion',
                    cordenadasName: 'ubicacion_cordenadas',
                    title: t('Location'),
                    paisStore: paisStore,
                    provinciaStore: provinciaStore
                }]
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de ubicaciones en el menú principal
Ext.define('RESCATE.panel.UbicacionLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.panel.UbicacionLista',
    title: t('List'),
    soloIconos: false,
    soloIconosEdicion: false,
    botonAgregar: CONFIG.perms.administrar_ubicaciones,
    botonEditar: CONFIG.perms.administrar_ubicaciones,
    botonBorrar: CONFIG.perms.administrar_ubicaciones,
    formulario: 'editarUbicacion',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'ubicacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'ubicacion_nombre',
        sortable: true
    }, {
        text: t('Country'),
        flex: 1,
        dataIndex: 'ubicacion_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('State/Province'),
        flex: 1,
        dataIndex: 'ubicacion_provincia',
        sortable: true
    }, {
        text: t('City'),
        flex: 1,
        dataIndex: 'ubicacion_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Address'),
        flex: 1,
        dataIndex: 'ubicacion_direccion',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'ubicacion_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Ubicacion', {
        });
        this.store.getProxy().extraParams.norel = 1;
        this.callParent(arguments);
    }
});
// grid con la lista de ubicaciones
Ext.define('RESCATE.grid.UbicacionLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.UbicacionLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_ubicaciones,
    botonEditar: CONFIG.perms.administrar_ubicaciones,
    botonBorrar: CONFIG.perms.administrar_ubicaciones,
    formulario: 'editarUbicacion',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'ubicacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'ubicacion_nombre',
        sortable: true
    }, {
        text: t('Country'),
        flex: 1,
        dataIndex: 'ubicacion_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('State/Province'),
        flex: 1,
        dataIndex: 'ubicacion_provincia',
        sortable: true
    }, {
        text: t('City'),
        flex: 1,
        dataIndex: 'ubicacion_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Address'),
        flex: 1,
        dataIndex: 'ubicacion_direccion',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'ubicacion_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Ubicacion', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de ubicaciones
Ext.define('RESCATE.grid.Ubicacion', {
    alias: 'widget.grid.Ubicacion',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Locations'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    registroUnico: true,
    formulario: 'editarUbicacionObra',
    mensajeAgregar: t('Add Location'),
    deleteParam1: 'ubicacion_id',
    deleteParam2: 'ubicacionobra_obra_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'ubicacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'ubicacion_nombre',
        sortable: true
    }, {
        text: t('Country'),
        flex: 1,
        dataIndex: 'ubicacion_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('State/Province'),
        flex: 1,
        dataIndex: 'ubicacion_provincia',
        sortable: true
    }, {
        text: t('City'),
        flex: 1,
        dataIndex: 'ubicacion_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Address'),
        flex: 1,
        dataIndex: 'ubicacion_direccion',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'ubicacion_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.UbicacionObra', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Ubicacion', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Ubicacion',
    title: '',
    idRelacion: 'campoIdObra',
    gridEditXtype: 'grid.Ubicacion',
    gridEditItemId: 'gridUbicacion',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'ubicacionGridDDGroup',
    gridListXtype: 'grid.UbicacionLista',
    gridListItemId: 'gridUbicacionLista'
});