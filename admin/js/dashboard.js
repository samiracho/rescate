Ext.define('RESCATE.container.Dashboard', {
    alias: 'widget.container.Dashboard',
    extend: 'Ext.Container',
    layout: {
		type: 'vbox',
		align : 'stretch'
	},
    border: 0,
    title: t('Start'),
    itemId: 'panelInicio',
	initComponent: function() {
        var me = this;
		var myItems = me._setItems();
			
        Ext.applyIf(me, {
            items:[{
				xtype:'panel.Tablon',
				flex:1
			},{
				layout: {
					type: 'hbox',
					columns: 'stretch'
				},
				flex:1,
				items: myItems
			}]
        });
        me.callParent(arguments);
    },
	_setItems: function()
	{
		var myItems = [];
		if(CONFIG.perms.administrar_registros) 
		{
			myItems.push({
				xtype:'container.Tareas',
				html:'a',
				flex:1
			});
		};
		
		if(CONFIG.perms.administrar_backups) 
		{
			myItems.push({
				xtype:'container.Backup',
				html:'a',
				flex:1
			});
		}

		myItems.push({
				xtype:'container.Tips',
				html:'a',
				flex:1
		});
		return myItems;
	}
});

Ext.define('RESCATE.container.Tareas', {
    alias: 'widget.container.Tareas',
    extend: 'Ext.Container',
    border: false,
	minHeight:200,
	minWidth:250,
	listeners: {
		beforerender: function(component)
		{
			Ext.Ajax.request(
			{
				url: 'datos/dashboard.php',
				method: 'POST',
				params: {'action': 'tasks'},
				success: function (o)
				{
					component.update (o.responseText);
				}
			});
		}
	}
});

Ext.define('RESCATE.container.Backup', {
    alias: 'widget.container.Backup',
    extend: 'Ext.Container',
    border: false,
	minHeight:200,
	minWidth:250,
	listeners: {
		beforerender: function(component)
		{
			Ext.Ajax.request(
			{
				url: 'datos/dashboard.php',
				method: 'POST',
				params: {'action': 'backup'},
				success: function (o)
				{
					component.update (o.responseText);
				}
			});
		}
	}
});

Ext.define('RESCATE.container.Tips', {
    alias: 'widget.container.Tips',
    extend: 'Ext.Container',
    border: false,
	minHeight:200,
	minWidth:250,
	listeners: {
		beforerender: function(component)
		{
			Ext.Ajax.request(
			{
				url: 'datos/dashboard.php',
				method: 'POST',
				params: {'action': 'tips'},
				success: function (o)
				{
					component.update (o.responseText);
				}
			});
		}
	}
});

Ext.define('RESCATE.panel.Tablon', {
	alias: 'widget.panel.Tablon',
    extend: 'Ext.Panel',
	layout: 'fit',
	title:t('News Board'),
	border:0,
    listeners: {
		beforerender: function(component)
		{
			if(!CONFIG.perms.administrar_tablon)return;
			var campo = component.down('#textoMensajes');
			
			Ext.Ajax.request(
			{
				url: 'datos/dashboard.php',
				method: 'POST',
				params: {'action': 'wall'},
				success: function (o)
				{			
					campo.setValue(o.responseText);
				},
				failure: function(o)
				{
					campo.setValue(t('Error loading News Board'));
				}
			});
		}
	},
	initComponent: function() {
        var me = this;
		var myItems = me._setItems();
			
        Ext.applyIf(me, {
            items: myItems
        });
        me.callParent(arguments);
    },
	_setItems: function()
	{
		var me = this;
		var myItems;
		
		if (!CONFIG.perms.administrar_tablon)
        {
			myItems = [{
				xtype: 'container',
				itemId:'textoMensajes',
				padding: 10,
				autoScroll: true,
				autoLoad: {
					url: 'datos/dashboard.php?action=wall',
					scripts: true,
					disableCaching: false
				}
			}];
		}else{
		
			me.buttonSave = new Ext.Button({
				iconCls:'iconoGuardar',
				tooltip:'<b>'+t('Save')+'</b>',
				handler: me.guardarCambios,
				scope: me
			});

			myItems = [{
				xtype: 'htmleditor',
				itemId:'textoMensajes',
				border:0,
				listeners:{
					'render':{
						fn: function(){	
							this.getToolbar().add(me.buttonSave);
						}
					}
				}
			}];
		}
		return myItems;
	},
	guardarCambios: function()
	{
		var me = this;
		var datos = me.down('#textoMensajes').getValue();
		
		// ponemos el icono loading
		me.buttonSave.setIconCls('cargandoOpcionArbol');	
		
		Ext.Ajax.request({
			url: 'datos/dashboard.php',
			method: 'POST',
			params: {'action': 'wall', 'data' : datos},
			success: function (o)
			{
				me.buttonSave.setIconCls('iconoGuardar');
				Ext.MessageBox.show(
				{
					title: t('Operation successfull'),
					msg: t('Changes saved successfully'),
					icon: 'iconoOK'
				});
				// cerramos automáticamente el mensaje de confirmación tras 1 seg			
				Ext.Function.defer(Ext.MessageBox.hide, 1000, Ext.MessageBox);
			},
			failure:function(response,options){
				me.buttonSave.setIconCls('iconoGuardar');
				Ext.MessageBox.show(
                {
                    title: t('Error'),
                    msg: t('Error saving changes'),
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
		});
	}
});