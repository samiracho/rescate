// formulario para editar técnicas
Ext.define('RESCATE.form.editarAsociacionProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarAsociacionProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Club'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
		var anyoStore = Ext.create('widget.store.Anyo', {});
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
                name: 'asociacion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'asociacionprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            },{
				xtype: 'form.ComboBox',
				displayField: 'anyo',
				valueField: 'anyo',
				name: 'asociacionprofesional_fechaentrada',
				store: anyoStore,
				fieldLabel: t('Start Date (Year)')
			},{
				xtype: 'form.ComboBox',
				displayField: 'anyo',
				valueField: 'anyo',
				name: 'asociacionprofesional_fechasalida',
				store: anyoStore,
				fieldLabel: t('End Date (Year)')
			},{
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -90',
                name: 'asociacionprofesional_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
		
		// Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
			anyoStore.load();
        });
		
        me.callParent(arguments);
    }
});
// formulario para editar asociaciones
Ext.define('RESCATE.form.editarAsociacion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarAsociacion',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Club'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
		var anyoStore = Ext.create('widget.store.Anyo', {});
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
                name: 'asociacion_id',
                hidden: true
            }, {
                xtype: 'textfield',
                fieldLabel: t('Name') + '*',
                name: 'asociacion_nombre',
                allowBlank: false
            }, {
				xtype: 'form.ComboBox',
				displayField: 'anyo',
				valueField: 'anyo',
				name: 'asociacion_fecha',
				store: anyoStore,
				fieldLabel: t('Date (Year)')
			}, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -90',
                name: 'asociacion_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
		
		// Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
			anyoStore.load();
        });
		
        me.callParent(arguments);
    }
});
// grid con la lista de asociaciones
Ext.define('RESCATE.grid.AsociacionLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.AsociacionLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_asociaciones,
    botonEditar: CONFIG.perms.administrar_asociaciones,
    botonBorrar: CONFIG.perms.administrar_asociaciones,
    formulario: 'editarAsociacion',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'asociacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'asociacion_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'asociacion_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Asociacion', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de asociaciones
Ext.define('RESCATE.grid.Asociacion', {
    alias: 'widget.grid.Asociacion',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Clubs'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarAsociacionProfesional',
    mensajeAgregar: t('Add Speciality'),
    deleteParam1: 'asociacion_id',
    deleteParam2: 'asociacionprofesional_profesional_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'asociacion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'asociacion_nombre',
        sortable: true
    }, {
        text: t('Start Date'),
        dataIndex: 'asociacionprofesional_fechaentrada',
        sortable: true
    }, {
        text: t('End Date'),
        dataIndex: 'asociacionprofesional_fechasalida',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'asociacion_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.AsociacionProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Asociacion', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Asociacion',
    title: t('Associations'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.Asociacion',
    gridEditItemId: 'gridAsociacion',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'asociacionGridDDGroup',
    gridListXtype: 'grid.AsociacionLista',
    gridListItemId: 'gridAsociacionLista'
});