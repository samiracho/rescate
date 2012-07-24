// grid con la lista de cargos
Ext.define('RESCATE.grid.Cargo', {
    alias: 'widget.grid.Cargo',
    extend: 'RESCATE.grid.grid',
    title: t('Jobs'),
    itemId: 'gridCargo',
    mensajeAgregar: t('Add Job'),
    soloIconos: true,
    formulario: 'editarCargo',
    idRelacion: 'campoIdProfesional',
    nombreRelacion: 'cargo_profesional_id',
    columns: [
    {
        text: t('Id'),
        flex: 1,
        dataIndex: 'cargo_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Category'),
        flex: 1,
        dataIndex: 'cargo_nombre',
        sortable: true
    }, {
        text: t('Department'),
        flex: 1,
        dataIndex: 'cargo_departamento',
        sortable: true
    }, {
        text: t('Main'),
        flex: 1,
        dataIndex: 'cargo_principal',
        sortable: true,
        renderer: function (val)
        {
            if (val == "1") return '<b>' + t('Yes') + '</b>';
            else
            return t('No')
        }
    }, {
        text: t('Date'),
        flex: 1,
        dataIndex: 'cargo_fechainicio',
        sortable: true,
        hidden: true
    }, {
        text: t('End Date'),
        flex: 1,
        dataIndex: 'cargo_fechafin',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'cargo_detalles',
        sortable: true,
        hidden: true
    }, {
        text: t('Center Name'),
        flex: 1,
        dataIndex: 'centro_nombre',
        sortable: true
    }, {
        text: t('Center Code'),
        flex: 1,
        dataIndex: 'centro_codigo',
        sortable: true,
        hidden: true
    }, {
        text: t('Center Details'),
        flex: 1,
        dataIndex: 'centro_detalles',
        sortable: true,
        hidden: true
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Cargo', {
        });
        this.callParent(arguments);
    }
});
// formulario para editar cargos
Ext.define('RESCATE.form.editarCargo', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarCargo',
    itemId: 'editarCargo',
    height: 421,
    width: 695,
    layout: {
        type: 'fit'
    },
    title: 'Cargo',
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
		var anyoStore = Ext.create('widget.store.Anyo', {actualmente:true});
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
                    title: t('Professional Details') + '*',
                    items: [
                    {
                        xtype: 'container',
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
                                xtype: 'textfield',
                                name: 'cargo_nombre',
                                fieldLabel: t('Name') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                name: 'cargo_departamento',
                                fieldLabel: t('Department') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'hiddenfield',
                                name: 'cargo_id'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'cargo_centro_id',
                                allowBlank: false
                            }]
                        }, {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
								{
									xtype: 'form.ComboBox',
									displayField: 'anyo',
									valueField: 'anyo',
									name: 'cargo_fechainicio',
									store: anyoStore,
									fieldLabel: t('Start Date (Year)')
								},{
									xtype: 'form.ComboBox',
									displayField: 'anyo',
									valueField: 'anyo',
									name: 'cargo_fechafin',
									itemId:'cargoFechaFin',
									store: anyoStore,
									fieldLabel: t('End Date (Year)')
								}, {
									xtype: 'checkboxfield',
									name: 'cargo_actualmente',
									boxLabel: t('Nowadays'),
									labelAlign: 'left',
									uncheckedValue: 0,
									hidden:true
								}, {
									xtype: 'checkboxfield',
									name: 'cargo_principal',
									boxLabel: t('Main Job'),
									labelAlign: 'left',
									uncheckedValue: 0
                            }]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'cargo_detalles',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'grid.CentroSeleccionar',
                    nameRelacionCentro: 'cargo_centro_id'
                }]
            }],
            dockedItems: me.barraBotones()
        }];
		
		// Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
			anyoStore.load();
			if( me.down('checkboxfield[name=cargo_actualmente]').getValue())me.down('#cargoFechaFin').setValue(t('Nowadays'));		
        });
		
        me.callParent(arguments);
    }
});