// grid con la lista de bibliografias
Ext.define('RESCATE.grid.Bibliografia', {
    alias: 'widget.grid.Bibliografia',
    extend: 'RESCATE.grid.grid',
    formulario: 'editarBibliografia',
    mensajeAgregar: t('Add Bibliography'),
    botonMarcar: true,
    stateful: true,
    stateId: 'stateGridBibliografia',
    title: t('Bibliographies'),
    idMarcar: 'bibliografia_supervisado',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'bibliografia_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'bibliografia_titulo',
        sortable: true
    }, {
        text: t('Date'),
        width: 110,
        dataIndex: 'bibliografia_fechaedicion',
        sortable: true,
        hidden: true
    },{
        text: t('Author(s)'),
        flex:1,
        dataIndex: 'bibliografia_id',
        sortable: false,
		searchable:false,
        renderer: function (val, metaData, record)
        {
			var text = '<ul class="gridCeldaLista">';
			var profesionales = record.profesionales();
			
			profesionales.each(function (autor)
			{
				var apellido2 = autor.get('profesional_apellido2') ? ' '+autor.get('profesional_apellido2') : '';
				text += '<li>'+autor.get('profesional_nombre')+' '+autor.get('profesional_apellido1')+apellido2+'</li>';	
			});
			text+= '</ul>';
			
			return text;
        }
    }, {
        text: t('Login'),
        flex: 1,
        dataIndex: 'usuario_login'
    }, {
        text: t('Author Name'),
        dataIndex: 'profesional_nombre',
        hidden: true,
		hideable:false
    }, {
        text: t('Author Surname 1'),
        dataIndex: 'profesional_apellido1',
        hidden: true,
		hideable:false
    }, {
        text: t('Author Surname 2'),
        dataIndex: 'profesional_apellido2',
        hidden: true,
		hideable:false
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
        text: t('Checked (0/1)'),
        width: 110,
        dataIndex: 'bibliografia_supervisado',
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
        dataIndex: 'bibliografia_bloqueado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoBloqueado"></div>';
            else
            return '<div class="iconoNoBloqueado"></div>';
        }
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        if (!CONFIG.perms.administrar_bibliografias && !CONFIG.perms.agregareditar_registros_propios)
        {
            this.botonAgregar = false;
            this.botonEditar = false;
        }
        this.store = Ext.create('widget.store.Bibliografia', {
        });
        this.callParent(arguments);
    },
    // al hacer doble click sobre una fila o hacer click sobre el botón editar. Override para controlar permisos
    onEditClick: function (button)
    {
        var record = this.getView().getSelectionModel().getSelection()[0];
        if (record)
        {
            if (CONFIG.perms.administrar_bibliografias || (record.data['bibliografia_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                // si está en modo lista no hacemos nada
                if (!this.botonEditar) return false;
                var record = this.getView().getSelectionModel().getSelection()[0];
                if (record) this.abrirFormularioEdicion(record);
            }
        }
        return false;
    },
    // al seleccionar una fila activamos los botones editar y eliminar siempre que el registro no esté bloqueado
    onSelectChange: function (selModel, selections)
    {
        if (selections.length != 0)
        {
            // si es administrador o el registro no está bloqueado
            if (CONFIG.perms.administrar_bibliografias || (selections[0].data['bibliografia_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
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
Ext.define('RESCATE.form.editarBibliografia', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarBibliografia',
    margin: '',
    padding: '',
    width: 780,
    height: 540,
    minHeight: 540,
    maximizable: true,
    title: t('Bibliography'),
    initComponent: function ()
    {
        var me = this;
        var creadorStore = Ext.create('widget.store.Creador', {
        });
		var anyoStore = Ext.create('widget.store.Anyo', {});
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
                                name: 'bibliografia_id',
                                itemId: 'campoIdBibliografia'
                            }, {
                                xtype: 'hiddenfield',
                                name: 'profesionalbibliografia_profesional_id',
                                itemId: 'campoIdProfesional'
                            }, {
                                xtype: 'textfield',
                                name: 'bibliografia_titulo',
                                fieldLabel: t('Title') + '*',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                name: 'bibliografia_isbn',
                                fieldLabel: '<a target="blank" href="http://www.mcu.es/libro/CE/AgenISBN.html">' + t('ISBN') + '*' + '</a>',
                                allowBlank: false,
								listeners: {
									scope: me,
									blur: function (field, options)
									{
										me.comprobarISBNUnico(field, options)
									} /*Usamos onblur en lugar de un vtype para comprobar solo cuando pierda el foco*/
								}
                            }, {
								xtype: 'form.ComboBox',
								displayField: 'anyo',
								valueField: 'anyo',
								name: 'bibliografia_fechaedicion',
								store: anyoStore,
								anchor:'100%',
								fieldLabel: t('Date (Year)')
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
                                name: 'bibliografia_editorial',
                                fieldLabel: t('Publisher')
                            }, {
                                xtype: 'textfield',
                                name: 'bibliografia_categorias',
                                fieldLabel: t('Categories')
                            }]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'bibliografia_detalles',
                        fieldLabel: t('Details'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'panel.ProfesionalBibliografia',
                    title: t('Authors & Related Professionals'),
                    itemId: 'panelProfesionales'
                }, {
                    xtype: 'panel',
                    padding: 6,
                    itemId: 'panelAdministracion',
                    title: t('Administration'),
                    items: [
                    {
                        xtype: 'checkboxfield',
                        name: 'bibliografia_supervisado',
                        fieldLabel: t('Checked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'checkboxfield',
                        name: 'bibliografia_bloqueado',
                        fieldLabel: t('Blocked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'id',
                        name: 'bibliografia_usuario_id',
                        store: creadorStore,
                        fieldLabel: t('Creator'),
                        emptyText: t('Me'),
                        width: 400
                    }, {
                        xtype: 'textfield',
                        name: 'bibliografia_ultimamod',
                        readOnly: true,
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
            creadorStore.load();
        });
        me.callParent(arguments);
    },
    EstablecerPermisos: function ()
    {
        var me = this;
		// Si no es administrador desactivamos el panel de administracion
        var tabAdministracion = me.down('#panelAdministracion');
        if (!CONFIG.perms.administrar_bibliografias)
        {
            this.BloqDebloqTab(tabAdministracion, true);
            tabAdministracion.tab.setTooltip(
            {
                title: t('Option Blocked'),
                text: t('You dont have the required permissions')
            });
        }
        var idBibliografia = me.down('[name=bibliografia_id]').getValue();
        var tabDetalles = me.down('#panelDetalles');
        var tabProfesionales = me.down('#panelProfesionales');
        var bloq = (idBibliografia == '') ? true : false;
        this.BloqDebloqTab(tabDetalles, bloq);
        this.BloqDebloqTab(tabProfesionales, bloq);
    },
	comprobarISBNUnico: function (field, options)
    {
        var ruta = 'datos/bibliografia.php';
        var idBibliografia = field.up('form').down('hiddenfield[name=bibliografia_id]').getValue();
        var parametros =
        {
            'action': 'checkIsbnUnique',
            'isbn': field.getValue(),
            'idBibliografia': idBibliografia
        };
        this.comprobarUnico(field, options, parametros, ruta);
    },
    //override del metodo que se ejecuta después de sincronizar
    SyncCallBack: function ()
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
                var campoIdProfesional = form.down('[name=bibliografia_id]');
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

// grid con la lista editable de profesionales relacionados con la bibliografia
Ext.define('RESCATE.grid.ProfesionalBibliografia', {
    alias: 'widget.grid.ProfesionalBibliografia',
    extend: 'RESCATE.grid.DropPro',
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarProfesionalBibliografia',
	title: t('Authors & Related Professionals'),
    mensajeAgregar: t('Add Authors & Related Professionals'),
	nombreRelacion: 'bibliografia_id',
	deleteParam1: 'profesionalbibliografia_id',
	claveAjena1: 'profesionalbibliografia_bibliografia_id',
	claveAjena2: 'profesionalbibliografia_profesional_id',
    registrosDuplicados: true,
	registroUnico: false,
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
        text: t('Relación'),
        width: 160,
        dataIndex: 'profesionalbibliografia_tiporelacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        width: 100,
        dataIndex: 'profesionalbibliografia_detalles',
        sortable: true,
        hidden: false
    }, {
        text: 'OK',
        width: 30,
        dataIndex: 'profesionalbibliografia_tiporelacion',
        sortable: true,
        renderer: function (value, metadata, record, rowIndex, colIndex, store)
        {
            if (value) return '<div class="iconoSupervisado"></div>';
            else
            {
                metadata.tdAttr = 'data-qtip="' + t('You must edit it to complete the details') + '"';
                return '<div class="iconoNoSupervisado"></div>';
            }
        }
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.ProfesionalBibliografia', {
        });
        this.callParent(arguments);
    }
});

// panel que une el grid de listado de profesionales y el grid editable de profesionales
Ext.define('RESCATE.panel.ProfesionalBibliografia', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.ProfesionalBibliografia',
    title: '',
    idRelacion: 'campoIdBibliografia',
    gridEditXtype: 'grid.ProfesionalBibliografia',
    gridEditItemId: 'gridProfesionalBibliografia',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'ProfesionalBibliografiaGridDDGroup',
    gridListXtype: 'grid.ProfesionalLista',
    gridListItemId: 'gridProfesionalLista'
});

//formulario editar profesionalbibliografia
Ext.define('RESCATE.form.editarProfesionalBibliografia', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProfesionalBibliografia',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Bibliography'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var tipoRelacion;
        var tiposStore = Ext.create('widget.store.TiposProfesionalBibliografia', {
        });
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
                name: 'profesionalbibliografia_bibliografia_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'nombre',
                name: 'profesionalbibliografia_tiporelacion',
                store: tiposStore,
                fieldLabel: t('Tipo Relación') + '*',
                emptyText: 'Relación',
                allowBlank: false
            }, {
                xtype: 'textfield',
                name: 'profesionalbibliografia_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'profesionalbibliografia_detalles',
                margin: '0 0 50 0'
            }],
            dockedItems: me.barraBotones()
        }];
        me.addListener('datoscargados', function (component)
        {
            tiposStore.load();
        });
        me.callParent(arguments);
    }
});