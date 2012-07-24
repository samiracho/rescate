// grid con la lista de formacions
Ext.define('RESCATE.grid.Formacion', {
    alias: 'widget.grid.Formacion',
    extend: 'RESCATE.grid.grid',
    title: t('Education'),
    itemId: 'gridFormacion',
    mensajeAgregar: t('Add Formation'),
    formulario: 'editarFormacion',
    soloIconos: true,
    idRelacion: 'campoIdProfesional',
    nombreRelacion: 'formacion_profesional_id',
    columns: [
    {
        text: t('Id'),
        flex: 1,
        dataIndex: 'formacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Title'),
        flex: 1,
        dataIndex: 'formacion_titulo',
        sortable: true
    }, {
        text: t('Date'),
        flex: 1,
        dataIndex: 'formacion_fechainicio',
        sortable: true,
        hidden: true
    }, {
        text: t('End Date'),
        flex: 1,
        dataIndex: 'formacion_fechafin',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'formacion_detalles',
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
        this.store = Ext.create('widget.store.Formacion', {
        });
        this.callParent(arguments);
    }
});
// formulario para editar formaciones
Ext.define('RESCATE.form.editarFormacion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarFormacion',
    itemId: 'editarFormacion',
    height: 421,
    width: 695,
    // itemId del grid al que está ligado este formulario
    layout: {
        type: 'fit'
    },
    title: t('Education'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
		var anyoStore = Ext.create('widget.store.Anyo', {actualmente:'true'});
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
                                name: 'formacion_titulo',
                                fieldLabel: t('Title') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'hiddenfield',
                                name: 'formacion_id'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'formacion_centro_id',
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
									name: 'formacion_fechainicio',
									store: anyoStore,
									fieldLabel: t('Start Date (Year)')
								},{
									xtype: 'form.ComboBox',
									displayField: 'anyo',
									valueField: 'anyo',
									name: 'formacion_fechafin',
									itemId:'formacionFechaFin',
									store: anyoStore,
									fieldLabel: t('End Date (Year)')
								}, {
									xtype: 'checkboxfield',
									name: 'formacion_actualmente',
									labelAlign: 'left',
									uncheckedValue: 0,
									hidden:true
								}
                      
                            ]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'formacion_detalles',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'grid.CentroSeleccionar',
                    nameRelacionCentro: 'formacion_centro_id'
                }]
            }],
            dockedItems: me.barraBotones()
        }];
		
		// Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
			anyoStore.load();
			if( me.down('checkboxfield[name=formacion_actualmente]').getValue())me.down('#formacionFechaFin').setValue(t('Nowadays'));		
        });
		
        me.callParent(arguments);
    }
});