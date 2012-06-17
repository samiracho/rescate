Ext.define('RESCATE.panel.Usuario', {
    extend: 'Ext.TabPanel',
    alias: 'widget.panel.Usuario',
    activeTab: 0,
    stateful: true,
    stateId: 'statePanelUsuarioRol',
    itemId: 'panelUsuario',
    layout: 'fit',
    items: [
    {
        xtype: 'grid.Usuario',
        itemId: 'gridUsuario'
    }, {
        xtype: 'grid.Rol'
    }],
    // para versiones de extjs superiores a la 4.07 necesito lanzar el evento activate la pestaña
	listeners: {
		activate: function (component)
		{
			
			gridUsuario = component.down('#gridUsuario');
			gridUsuario.fireEvent('activate', gridUsuario);
		}
	}
});
Ext.define('RESCATE.form.editarUsuario', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarUsuario',
    width: 570,
    resizable: false,
    title: t('Edit User'),
    initComponent: function ()
    {
        var roles = Ext.create('widget.store.Rol', {
        });
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                labelWidth: 110,
                anchor: '100%'
            },
            bodyPadding: 5,
            items: [
            {
                xtype: 'hidden',
                name: 'usuario_id'
            }, {
                xtype: 'container',
                anchor: '100%',
                layout: 'column',
                items: [
                {
                    xtype: 'container',
                    columnWidth: .5,
                    layout: 'anchor',
                    items: [
                    {
                        xtype: 'textfield',
                        anchor: '96%',
                        name: 'usuario_nombre',
                        fieldLabel: t('Name') + '*',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        anchor: '96%',
                        name: 'usuario_apellido1',
                        fieldLabel: t('Surname')
                    }, {
                        xtype: 'textfield',
                        anchor: '96%',
                        name: 'usuario_apellido2',
                        fieldLabel: t('Surname 2')
                    }, {
                        xtype: 'textfield',
                        anchor: '96%',
                        name: 'usuario_email',
                        fieldLabel: t('Email'),
                        vtype: 'email'
                    }]
                }, {
                    xtype: 'container',
                    columnWidth: .5,
                    layout: 'anchor',
                    items: [
                    {
                        xtype: 'textfield',
                        name: 'usuario_login',
                        fieldLabel: t('Login') + '*',
                        allowBlank: false,
                        listeners: {
                            scope: me,
                            blur: function (field, options)
                            {
                                me.comprobarNombreUnico(field, options)
                            } /*Usamos onblur en lugar de un vtype para comprobar solo cuando pierda el foco*/
                        }
                    }, {
                        xtype: 'textfield',
                        inputType: 'password',
                        name: 'usuario_password',
                        allowBlank: false,
                        itemId: 'pass',
                        fieldLabel: t('Password') + '*'
                    }, {
                        xtype: 'textfield',
                        inputType: 'password',
                        name: 'usuario_password2',
                        vtype: 'password',
                        allowBlank: false,
                        itemId: 'pass2',
                        initialPassField: 'pass',
                        fieldLabel: t('Retype password') + '*'
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'rol_nombre',
                        valueField: 'rol_id',
                        name: 'usuario_rol_id',
                        store: roles,
                        fieldLabel: t('Rol') + '*',
                        emptyText: 'Rol no asignado',
                        itemId: 'comboRoles',
                        allowBlank: false
                    }]
                }]
            }, {
                xtype: CONFIG.textArea,
                name: 'usuario_detalles',
                fieldLabel: t('Details'),
                height: 200
            }],
            dockedItems: me.barraBotones()
        }];
        // seleccionamos el rol del usuario en el combobox. Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario		
        me.addListener('datoscargados', function (component)
        {
            me.establecerRol(component);
            me.establecerPassword(component);
        });
        me.callParent(arguments);
    },
    // si el usuario no es nuevo seleccionamos el rol que le corresponda
    establecerRol: function (component)
    {
        component.down('#comboRoles').store.load();
    },
    // si el usuario no es nuevo escribimos el password2
    establecerPassword: function (component)
    {
        var idUsuario = eval(component.down('hiddenfield[name=usuario_id]').getValue());
        if (idUsuario != '')
        {
            component.down('#pass2').setValue(component.down('#pass').getValue());
        }
    },
    comprobarNombreUnico: function (field, options)
    {
        var ruta = 'datos/usuario.php';
        var idUsuario = field.up('form').down('hiddenfield[name=usuario_id]').getValue();
        var parametros =
        {
            'action': 'checkUnique',
            'login': field.getValue(),
            'idUsuario': idUsuario
        };
        this.comprobarUnico(field, options, parametros, ruta);
    }
});
// grid con la lista de usuarios
Ext.define('RESCATE.grid.Usuario', {
    alias: 'widget.grid.Usuario',
    extend: 'RESCATE.grid.grid',
    title: 'Lista Usuarios',
    itemId: 'gridUsuario',
    stateful: true,
    stateId: 'statePanelUsuario',
    formulario: 'editarUsuario',
    mensajeAgregar: t('Add User'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'usuario_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'usuario_nombre',
        sortable: true
    }, {
        text: t('login'),
        width: 160,
        dataIndex: 'usuario_login',
        sortable: true
    }, {
        text: t('email'),
        width: 160,
        dataIndex: 'usuario_email',
        sortable: true
    }, {
        text: t('Surname 1'),
        width: 160,
        dataIndex: 'usuario_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        width: 160,
        dataIndex: 'usuario_apellido2',
        sortable: true
    }, {
        text: t('Rol'),
        width: 160,
        dataIndex: 'rol_nombre',
        sortable: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Usuario', {
        });
        this.callParent(arguments);
    }
});
Ext.define('RESCATE.form.editarRol', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarRol',
    title: t('Edit Rol'),
    width: 650,
    resizable: false,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                labelWidth: 110,
                anchor: '100%'
            },
            bodyPadding: 5,
            items: [
            {
                xtype: 'hidden',
                name: 'rol_id',
                itemId: 'campoIdRol'
            }, {
                xtype: 'hidden',
                name: 'rol_basico'
            }, {
                xtype: 'textfield',
                anchor: '100%',
                name: 'rol_nombre',
                allowBlank: false,
                fieldLabel: t('Name') + '*',
                listeners: {
                    scope: me,
                    blur: function (field, options)
                    {
                        me.comprobarNombreUnico(field, options)
                    } /*Usamos onblur en lugar de un vtype para comprobar solo cuando pierda el foco*/
                }
            }, {
                xtype: 'checkboxgroup',
                itemId: 'checkboxgroupPermisos',
                fieldLabel: t('Permissions'),
                flex: 1,
                columns: 2
            }, {
                xtype: CONFIG.textArea,
                name: 'rol_descripcion',
                fieldLabel: t('Description'),
                height: 200
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    },
    CargarRecord: function (record)
    {
        var ventana = this;
        // preguntamos al servidor por la lista de permisos disponibles
        Ext.Ajax.request(
        {
            url: 'datos/rol.php',
            method: 'GET',
            params: {
                'action': 'readPerms'
            },
            success: function (response, request)
            {
                var json = Ext.decode(response.responseText);
                if (json)
                {
                    if (json.success)
                    {
                        var formulario = ventana.down('form');
                        var checkboxgroup = formulario.down('#checkboxgroupPermisos');
						
						// lo vaciamos. Como le estoy diciendo que al cerrar la ventana no la destruya para ahorrar memoria en internet explorer, tengo que recrear los checkbox
                        checkboxgroup.removeAll();
                        // cargamos los datos en el formulario
                        formulario.loadRecord(record);
                        // recorremos la lista de permisos obtenida de la bd y creamos un checkbox para cada uno de ellos
                        var items = new Array();
                        for (i = 0; i < json.data.length; i++)
                        { 
                            var checkbox =
                            {
                                xtype: 'checkbox',
                                model: json.data[i],
                                // aquí nos guardamos el valor completo para acceder más tarde
                                boxLabel: json.data[i].permiso_nombre,
                                name: json.data[i].permiso_nombreInterno,
                                desc: json.data[i].permiso_descripcion,
                                checked: record.data.rol_permisos != '' ? record.data.rol_permisos[i].permiso_activo : 0,
                                // si es un record nuevo no marcaremos ningún checkbox, en caso contrario marcaremos los que tengan el valor "activo"=1
                                handler: function ()
                                {
                                    record.setDirty()
                                },
                                // si el usuario ha hecho clic en algún checkbox, marcamos el record como modificado para que al darle a guardar se guarden los cambios en la bd
                                listeners: {
                                    render: function (c)
                                    {
                                        Ext.tip.QuickTipManager.register(
                                        {
                                            target: c.getEl(),
                                            text: c.desc
                                        });
										this.setValue(this.checked); /* En 4.1rc tengo que llamar a esto para que actualice el valor checked*/
                                    }
                                }
                            };
                            items.push(checkbox);
                        };
						
						
						
                        // insertamos los checkbox que hemos generado
                        checkboxgroup.insert(0, items);
						
						console.log(checkboxgroup);
						
                        // repintamos el formulario para que se muestren los checkbox que hemos añadido dinámicamente				
                        formulario.doLayout();
                        
						//  mostramos el formulario
                        ventana.show();
                    }
                    else
                    {
                        var mensaje = json.message;
                        if (typeof json.errors == 'string') mensaje += '<br />' + json.errors;
                        // mostramos el error
                        Ext.MessageBox.show(
                        {
                            title: t('REMOTE EXCEPTION'),
                            msg: mensaje,
                            icon: Ext.MessageBox.ERROR,
                            buttons: Ext.Msg.OK
                        });
                    }
                }
                else
                {
                    // desbloqueamos la interfaz
                    Ext.getBody().unmask();
                    // mostramos el error
                    Ext.MessageBox.show(
                    {
                        title: t('REMOTE EXCEPTION'),
                        msg: t('internal error'),
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            },
            failure: function (response, request)
            { // no recibe respuesta del servidor
                // mostramos el mensaje de error
                Ext.MessageBox.show(
                {
                    title: t('REMOTE EXCEPTION'),
                    msg: response.responseText,
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        });
    },
    GuardarCambios: function (button)
    {
        // tenemos que actualizar los cambios en los permisos
        var win = button.up('window');
        var form = win.down('form');
        var checkboxgroup = form.down('#checkboxgroupPermisos');
        var record = form.getRecord();
        var values = form.getValues();
        // si es un registro nuevo recreamos el array de permisos
        if (record.phantom) record.data.rol_permisos = new Array(checkboxgroup.items.items.length);
        for (i = 0; i < checkboxgroup.items.items.length; i++)
        {
            if (record.phantom) record.data.rol_permisos[i] = checkboxgroup.items.items[i].model;
            record.data.rol_permisos[i].permiso_activo = checkboxgroup.items.items[i].value ? 1 : 0;
        }
        // llamamos a la función guardar del componente padre
        this.callParent(arguments);
    },
    comprobarNombreUnico: function (field, options)
    {
        var ruta = 'datos/rol.php';
        var idRol = field.up('form').down('hiddenfield[name=rol_id]').getValue();
        var parametros =
        {
            'action': 'checkUnique',
            'nombreRol': field.getValue(),
            'idRol': idRol
        };
        this.comprobarUnico(field, options, parametros, ruta);
    }
});
// grid con la lista de roles
Ext.define('RESCATE.grid.Rol', {
    alias: 'widget.grid.Rol',
    extend: 'RESCATE.grid.grid',
    title: t('Roles list'),
    itemId: 'gridRol',
    formulario: 'editarRol',
    stateful: true,
    stateId: 'statePanelRol',
    mensajeAgregar: t('Add Rol'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'rol_id',
        sortable: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'rol_nombre',
        sortable: true
    }, {
        text: t('Details'),
        flex: 2,
        dataIndex: 'rol_descripcion',
        sortable: true
    }, {
        text: t('Basic'),
        width: 180,
        dataIndex: 'rol_basico',
        sortable: true
    }],
    // sobreescribimos la función. Al seleccionar una fila activamos los botones editar y eliminar (el boton borrar lo desactivamos para los roles básicos porque estos no pueden borrarse)
    onSelectChange: function (selModel, selections)
    {
        this.down('#botonBorrar').setDisabled(selections.length === 0 || selections[0].data["basico"] == 1);
        this.down('#botonEditar').setDisabled(selections.length === 0);
    },
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Rol', {
        });
        this.callParent(arguments);
    }
});