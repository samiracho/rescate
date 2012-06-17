// grid con la lista de reconocimientos
Ext.define('RESCATE.grid.Reconocimiento', {
    alias: 'widget.grid.Reconocimiento',
    extend: 'RESCATE.grid.grid',
    title: t('Awards'),
    mensajeAgregar: t('Add Award'),
    formulario: 'editarReconocimiento',
    soloIconos: true,
    idRelacion: 'campoIdProfesional',
    nombreRelacion: 'reconocimiento_profesional_id',
    columns: [
    {
        text: t('Id'),
        flex: 1,
        dataIndex: 'reconocimiento_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'reconocimiento_nombre',
        sortable: true
    }, {
        text: t('Date'),
        flex: 1,
        dataIndex: 'reconocimiento_fecha',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'reconocimiento_detalles',
        sortable: true
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Reconocimiento', {
        });
        this.callParent(arguments);
    }
});
// formulario para editar reconocimientos
Ext.define('RESCATE.form.editarReconocimiento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarReconocimiento',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Award'),
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
                name: 'reconocimiento_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'hiddenfield',
                name: 'reconocimiento_id'
            }, {
                xtype: 'textfield',
                fieldLabel: t('Name') + '*',
                name: 'reconocimiento_nombre',
                allowBlank: false
            }, {
				xtype: 'form.ComboBox',
				displayField: 'anyo',
				valueField: 'anyo',
				name: 'reconocimiento_fecha',
				store: anyoStore,
				fieldLabel: t('Date (Year)')
			}, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -90',
                name: 'reconocimiento_detalles'
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