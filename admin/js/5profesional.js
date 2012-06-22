// grid con la lista de profesionales
Ext.define('RESCATE.grid.Profesional', {
    extend: 'RESCATE.grid.grid',
    /*Tipo de profesional que listaremos*/
    tipoProfesional: '',
    botonMarcar: true,
    /*Campo supervisado del modelo que utiliza este grid. Lo utlizaremos para marcar en rojo las filas no supervisadas al hacer click sobre el botón Marcar*/
    idMarcar: 'profesional_supervisado',
    /*store que se utilizara*/
    storeGrid: 'widget.store.Profesional',
    formulario: 'editarProfesional',
    mensajeAgregar: t('Add Restorer'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'profesional_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        flex: 1,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        flex: 1,
        dataIndex: 'profesional_apellido2',
        sortable: true
    }, {
        text: t('Login'),
        flex: 1,
        dataIndex: 'usuario_login',
        sortable: true
    }, {
        text: t('Creator Name'),
        flex: 1,
        dataIndex: 'usuario_nombre',
        sortable: true,
        hidden: true
    }, {
        text: t('Creator Surname 1'),
        flex: 1,
        dataIndex: 'usuario_apellido1',
        sortable: true,
        hidden: true
    }, {
        text: t('Creator Surname 2'),
        flex: 1,
        dataIndex: 'usuario_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Country of Birth'),
        flex: 1,
        dataIndex: 'profesional_paisn',
        sortable: true,
        hidden: true
    }, {
        text: t('State of Birth'),
        flex: 1,
        dataIndex: 'profesional_provincian',
        sortable: true,
        hidden: true
    }, {
        text: t('City of Birth'),
        flex: 1,
        dataIndex: 'profesional_poblacionn',
        sortable: true,
        hidden: true
    }, {
        text: t('Country of Death'),
        flex: 1,
        dataIndex: 'profesional_paisd',
        sortable: true,
        hidden: true
    }, {
        text: t('State of Death'),
        flex: 1,
        dataIndex: 'profesional_provinciad',
        sortable: true,
        hidden: true
    }, {
        text: t('City of Death'),
        flex: 1,
        dataIndex: 'profesional_poblaciond',
        sortable: true,
        hidden: true
    }, {
        text: t('Checked (0/1)'),
        width: 110,
        dataIndex: 'profesional_supervisado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoSupervisado"></div>';
            else
            return '<div class="iconoNoSupervisado"></div>'
        }
    }, {
        text: t('Blocked (0/1)'),
        width: 110,
        dataIndex: 'profesional_bloqueado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoBloqueado"></div>';
            else
            return '<div class="iconoNoBloqueado"></div>'
        }
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        var me = this;
        if (!CONFIG.perms.administrar_registros && !CONFIG.perms.agregareditar_registros_propios)
        {
            this.botonAgregar = false;
            this.botonEditar = false;
        }
        this.store = Ext.create(me.storeGrid, {});
        this.store.getProxy().extraParams.tipo = this.tipoProfesional;
        this.callParent(arguments);
    },
    // al hacer doble click sobre una fila o hacer click sobre el botón editar. Override para controlar permisos
    onEditClick: function (button)
    {
        var record = this.getView().getSelectionModel().getSelection()[0];
        if (record)
        {
            if (CONFIG.perms.administrar_registros || (record.data['profesional_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                // si está en modo lista no hacemos nada
                if (!this.botonEditar) return false;
                var record = this.getView().getSelectionModel().getSelection()[0];
                if (record) this.abrirFormularioEdicion(record);
            }else{
				Ext.MessageBox.alert(
				{
					title: t('Warning'),
					msg: t('You dont have the required permissions'),
					buttons: Ext.MessageBox.OK,
					icon: Ext.MessageBox.ERROR
				});		
			}
        }
        return false;
    },
    onAddClick: function ()
    {
        if (!this.botonAgregar) return false;
        // creamos un record vacío
        var record = Ext.create(this.getStore().model, {
        });
        // especificamos el tipo de profesional
        record.set('profesional_tipo', this.tipoProfesional);
        this.abrirFormularioEdicion(record);
    },
    // al seleccionar una fila activamos los botones editar y eliminar siempre que el registro no esté bloqueado
    onSelectChange: function (selModel, selections)
    {
        if (selections.length != 0)
        {
            // si es administrador o el registro no está bloqueado
            if (CONFIG.perms.administrar_registros || (selections[0].data['profesional_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(selections.length === 0);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(selections.length === 0);
            }
            else
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(true);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(true);
            }
        }
    }
});

Ext.define('RESCATE.Menu.Profesional', {
    alias: 'widget.menuOpcionesProfesional',
    extend: 'RESCATE.toolbar.ToolbarOpciones',
    itemIdContenedor: 'containerDetalles',
    initComponent: function ()
    {
        var me = this;
        var menu = Ext.create('Ext.menu.Menu', {
            items: [
            {
                text: t('Education'),
                panelId: 'widget.grid.Formacion',
                iconCls: 'iconoFormacion',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Specialities'),
                panelId: 'widget.container.Especialidad',
                iconCls: 'iconoEspecialidad',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Technnics'),
                panelId: 'widget.panel.Tecnica',
                iconCls: 'iconoTecnica',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Equipping'),
                panelId: 'widget.panel.Equipamiento',
                iconCls: 'iconoEquipamiento',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Jobs'),
                panelId: 'widget.grid.Cargo',
                iconCls: 'iconoCargo',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Documents'),
                panelId: 'widget.panel.DocumentoProfesional',
                iconCls: 'iconoDocumento',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Collaborators'),
                panelId: 'widget.panel.Colaborador',
                iconCls: 'iconoColaborador',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Awards'),
                panelId: 'widget.grid.Reconocimiento',
                iconCls: 'iconoGalardon',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }, {
                text: t('Associations'),
                panelId: 'widget.panel.Asociacion',
                iconCls: 'iconoAsociacion',
                handler: me.onItemClick,
                enableToggle: true,
                scope: me
            }]
        });
        me.items = [
        {
            text: t('Options Menu'),
            menu: menu,
            iconCls: 'iconoOpciones'
        }];
        me.callParent(arguments);
    }
});


Ext.define('RESCATE.form.editarProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProfesional',
    margin: '',
    padding: '',
    width: 770,
    height: 540,
    maximizable: true,
    mostrarDetalles: true,
    title: t('Restorers'),
    initComponent: function ()
    {
        var me = this;
        var sexoStore = Ext.create('widget.store.Sexo', {
        });
        var creadorStore = Ext.create('widget.store.Creador', {
        });
		
        var panelDetalles =
        {
            xtype: 'panel',
            padding: '',
            layout: {
                align: 'stretch',
                type: 'hbox'
            },
            title: t('Details'),
            itemId: 'panelDetalles',
            // como no estamos destruyendo el contenido del container, ponemos como activo un container vacío y así al seleccionar una opción refrescará el grid
            listeners: {
                activate: function (component)
                {
                    component.down('#containerDetalles').layout.setActiveItem(0);
                    component.down('#menuOpcionesProfesional').down('button').showMenu();
                }
            },
            dockedItems: {
                xtype: 'menuOpcionesProfesional',
                itemId: 'menuOpcionesProfesional'
            },
            items: [
            {
                xtype: 'container',
                itemId: 'containerDetalles',
                layout: {
                    type: 'card'
                },
                border: 0,
                flex: 1,
                items: {
                    xtype: 'container',
					hidden:true
                },
                viewConfig: {
                    loadMask: false // en formularios complejos se produce un error si esta opción está activada. En la versión 4.1 deberían haberlo resuelto
                }
            }]
        };
		
        if (!me.mostrarDetalles) panelDetalles =
        {
            hidden: true
        };
        me.items = [
        {
            xtype: 'form',
            bodyPadding: 10,
            layout: {
                type: 'fit'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '96%'
            },
            title: '',
            items: [
            {
                xtype: 'tabpanel',
                deferredRender: true,
                activeTab: 0,
                plain: true,
                items: [
                {
                    xtype: 'panel',
                    layout: {
                        align: 'stretch',
                        padding: 6,
                        type: 'vbox'
                    },
                    title: t('General') + '*',
                    items: [
                    {
                        xtype: 'container',
                        layout: 'hbox',
                        fieldDefaults: {
                            labelAlign: 'top',
                            msgTarget: 'side'
                        },
                        defaults: {
                            border: false,
                            flex: 1,
                            layout: 'anchor'
                        },
                        items: [
                        {
							border:true,
							xtype:'fieldset',
							title:'Foto',
							padding: 4,
							flex:0,
                            width: 138,
                            margin: 4,
							items: [
                            {
                                xtype: 'image',
                                itemId: 'vistaPrevia',
                                width: 128,
                                height: 128
                            }, {
                                xtype: 'button',
                                itemId: 'botonAdjuntar',
                                text: t('Attach...'),
                                tooltip: t('Attach File'),
                                width: 124,
                                scope: me,
                                handler: me.onAttachClick
                            }]	
						},{
                            items: [
                            {
                                xtype: 'textfield',
                                name: 'profesional_nombre',
                                fieldLabel: t('Name') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                name: 'profesional_apellido1',
                                fieldLabel: t('Surname 1') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                name: 'profesional_apellido2',
                                fieldLabel: t('Surname 2')
                            }, {
                                xtype: 'hiddenfield',
                                name: 'profesional_archivo'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'profesional_miniatura'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'profesional_id',
                                itemId: 'campoIdProfesional',
                                value: ''
                            }]
                        }, {
                            items: [
                            {
                                xtype: 'form.ComboBox',
                                displayField: 'nombre',
                                valueField: 'id',
                                name: 'profesional_sexo',
                                store: sexoStore,
                                fieldLabel: t('Sex') + '*',
                                value: 'Hombre',
                                emptyText: t('Sex'),
                                allowBlank: false
                            }, {
                                xtype: 'form.DateField',
                                name: 'profesional_fechan',
                                fieldLabel: t('Birth Date'),
                                itemId: 'startdt',
                                vtype: 'daterange',
                                endDateField: 'enddt'
                            }, {
                                xtype: 'form.DateField',
                                name: 'profesional_fechad',
                                fieldLabel: t('Date of Death'),
                                itemId: 'enddt',
                                vtype: 'daterange',
                                startDateField: 'startdt'
                            }]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'profesional_observaciones',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'panelDireccion',
                    paisName: 'profesional_paisn',
                    provinciaName: 'profesional_provincian',
                    poblacionName: 'profesional_poblacionn',
                    direccionName: 'profesional_direccionn',
                    cordenadasName: 'profesional_cordenadasn',
                    title: t('Birth Location')
                }, {
                    xtype: 'panelDireccion',
                    paisName: 'profesional_paisd',
                    provinciaName: 'profesional_provinciad',
                    poblacionName: 'profesional_poblaciond',
                    direccionName: 'profesional_direcciond',
                    cordenadasName: 'profesional_cordenadasd',
                    title: t('Death Location')
                },
				panelDetalles,
				{
                    xtype: 'panel',
                    padding: 6,
                    itemId: 'panelAdministracion',
                    title: t('Administration'),
                    items: [
                    {
                        xtype: 'checkboxfield',
                        name: 'profesional_supervisado',
                        fieldLabel: t('Checked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'checkboxfield',
                        name: 'profesional_bloqueado',
                        fieldLabel: t('Blocked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
						xtype: 'form.ComboBox',
						name: 'profesional_tipo',
						store: ['Colaborador','Restaurador','Especialista'],
						fieldLabel: t('Type') + '*',
						allowBlank: false,
						editable:false
					}, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'id',
                        name: 'profesional_usuario_id',
                        store: creadorStore,
                        fieldLabel: t('Creator'),
                        emptyText: t('Me'),
                        width: 400
                    }, {
                        xtype: 'textfield',
                        name: 'profesional_ultimamod',
                        readOnly: true,
                        submitValue: false,
                        disabled: true,
                        fieldLabel: t('Last Modification'),
                        width: 200
                    }]
                }]
            }],
            dockedItems: me.barraBotones()
        }];
        // Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
            me.down('#vistaPrevia').setSrc(me.down('[name=profesional_miniatura]').getValue());
			creadorStore.load();
        });
        me.callParent(arguments);
    },
    EstablecerPermisos: function ()
    {
        var me = this;
		// Si no es administrador desactivamos el panel de administracion
        var tabAdministracion = me.down('#panelAdministracion');
        if (!CONFIG.perms.administrar_registros)
        {
            this.BloqDebloqTab(tabAdministracion, true);
            tabAdministracion.tab.setTooltip(
            {
                title: t('Option Blocked'),
                text: t('You dont have the required permissions')
            });
        }
        var idProfesional = me.down('[name=profesional_id]').getValue();
        var tabDetalles = me.down('#panelDetalles');
        var bloq = (idProfesional == '') ? true : false;
        this.BloqDebloqTab(tabDetalles, bloq);
			
        var botonAdjuntar = me.down('#botonAdjuntar');
        if (idProfesional == '')botonAdjuntar.setDisabled(true);
        else botonAdjuntar.setDisabled(false);	
    },
    // al hacer click sobre el botón adjuntar
    onAttachClick: function (button, toggle)
    {
        var profesionalId = this.down('[name=profesional_id]').getValue();
        if (profesionalId == '') return;
        if (!this.subirArchivo)
        {
            this.subirArchivo = Ext.widget('window.subirArchivo',{url:'datos/profesional.php?action=upload'});
        }
        this.subirArchivo.down('[name=id]').setValue(profesionalId);
        this.subirArchivo.vistaPrevia = this.down('#vistaPrevia');
        this.subirArchivo.show();
    },
    //override del metodo que se ejecuta después de sincronizar
    SyncCallBack: function (batch, options)
    {
        var me = this;
		Ext.getBody().unmask();
        var form = me.down('form');
        var record = form.getRecord();
        var store = me.grid.getStore();
        store.proxy.ventana = null;
        Ext.MessageBox.confirm(t('Confirmation'), t('Success saving <br />Do you want to continue editing?'), function (btn)
        {
            if (btn == 'no')
            {
                me.close();
            }
            if (btn == 'yes')
            {
                var campoIdProfesional = form.down('[name=profesional_id]');
                // si era un registro nuevo actualizamos la id del profesional en el formulario para poder activar las tabs
                if (campoIdProfesional.getValue() == '')
                {
                    // esto debo hacerlo para que al volver a darle a aceptar, actualice el record que habíamos insertado previamente
                    // sino considera que no hay nada que actualizar y no hace el sync
                    form.loadRecord(store.getAt(0));
                }
                me.EstablecerPermisos();
            }
        });
    }
});
// grid con la lista de Restauradores
Ext.define('RESCATE.panel.Restaurador', {
    alias: 'widget.panel.Restaurador',
    extend: 'RESCATE.grid.Profesional',
    title: t('Restorers List'),
    itemId: 'panelRestaurador',
    stateful: true,
    stateId: 'statePanelRestaurador',
    tipoProfesional: 'Restaurador'
});
// grid con la lista de Colaboradores
Ext.define('RESCATE.panel.Otros', {
    alias: 'widget.panel.Otros',
    extend: 'RESCATE.grid.Profesional',
    title: 'Lista Otros Colaboradores',
    itemId: 'panelColaborador',
    stateful: true,
    stateId: 'statePanelColaborador',
    tipoProfesional: 'Colaborador'
});
// grid con la lista de Especialistas
Ext.define('RESCATE.panel.Especialista', {
    alias: 'widget.panel.Especialista',
    extend: 'RESCATE.grid.Profesional',
    title: 'Lista Especialistas',
    itemId: 'panelEspecialista',
    stateful: true,
    stateId: 'statePanelEspecialista',
    tipoProfesional: 'Especialista'
});

// grid con la lista de todos los profesionales
Ext.define('RESCATE.grid.ProfesionalLista', {
    extend: 'RESCATE.grid.Profesional',
    alias: 'widget.grid.ProfesionalLista',
    tipoProfesional: 'Todos',
    botonMarcar: false,
    soloIconos: true,
    soloIconosEdicion: true,
    idMarcar: '',
    storeGrid: 'widget.store.Profesional',
    formulario: 'editarAutor',
    title: t('List'),
    mensajeAgregar: t('Add Author'),
    columns: [
    {
        text: t('Name'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        width: 160,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        width: 160,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Type'),
        width: 160,
        dataIndex: 'profesional_tipo',
        sortable: true,
        hidden: true
    }]
});

Ext.define('RESCATE.form.editarAutor', {
    extend: 'RESCATE.form.editarProfesional',
    alias: 'widget.form.editarAutor',
    mostrarDetalles: false,
    title: t('Author')
});

// grid preparado para arrastar records de tipo profesional sobre él
// he hecho un override del método beforedrop para este caso en particular
Ext.define('RESCATE.grid.DropPro', {
    alias: 'widget.grid.DropPro',
    extend: 'RESCATE.grid.Drop',
    // itemId del campo que guarda el valor de la relación
    idRelacion: '',
	claveAjena1:null,
	claveAjena2:null,
	beforeDrop: function (node, data, model, pos, drop, view)
	{						
		var me = this;
		// id con la que relacionaremos el campo
		var id = me.up('form').down('#' + me.idRelacion).getValue();
		
		// evitamos que arrastre objetos con la propiedad allowDrag = false
		if (data.records[0].data.allowDrag === false) return false;
		
		// si solo se permite un registro
		if (me.getStore().count() > 0 && me.registroUnico)
		{
			Ext.MessageBox.alert(
			{
				title: t('Warning'),
				msg: t('This list can contain only one value'),
				buttons: Ext.MessageBox.OK
			});
			return false;
		}

		Ext.MessageBox.confirm(t('Confirmation'), t('Do you want to add the relation?'), function (btn)
		{
			if (btn == 'yes')
			{
				var plugin = this.getPlugin('ddplugin'),
				dropZone = plugin.dropZone;
				// reset this since these props were deleted by the 'return false'
				dropZone.overRecord = model;
				dropZone.currentPosition = pos;					
				
				if (me.idRelacion != '')
				{
					var record = Ext.create(me.getStore().model, {});
					record.beginEdit();
					record.fields.each(function (field)
					{
						record.set(field.name, data.records[0].get(field.name) );
					});
					if(me.claveAjena1)record.set(me.claveAjena1, id );
					if(me.claveAjena2)record.set(me.claveAjena2, data.records[0].getId());
					record.endEdit();
					data.records[0] = record;
					
					// evitamos que arrastre registros duplicados
					if(!me.registrosDuplicados)
					{
						me.getStore().each(function(rec){				

							if(rec.get(me.claveAjena1) != id && rec.get(me.claveAjena2) == data.records[0].getId() ){
								Ext.MessageBox.alert(
								{
									title: t('Warning'),
									msg: t('Duplicated value not allowed'),
									buttons: Ext.MessageBox.OK
								});
								return false;
							}
					   });  
					}
					
					// en extjs 4.1 hay que llamar a drop.processDrop() y no a drop() así que hacemos la comprobación
					(typeof drop.processDrop === 'function') ? drop.processDrop() : drop();
				}
				else return false;
			}
		}, view);
		// go ahead and return false for now to stop the drop
		// since we will handle the drop later ourselves
		return false;
	}
});