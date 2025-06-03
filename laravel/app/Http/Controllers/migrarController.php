<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class migrarController extends Controller
{
    //
    public function index()
    {
        set_time_limit(300);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); //Desactivar chequeo de FKs para poder truncar las tablas
        //Truncando las tablas para no tener datos repetidos
        DB::table('tipo_ot')->truncate();
        DB::table('prioridad_ot')->truncate();
        DB::table('estado_ot')->truncate();
        DB::table('tipo_visita')->truncate();
        DB::table('tipo_servicio')->truncate();
        DB::table('categoria')->truncate();
        DB::table('subcategoria')->truncate();
        DB::table('linea')->truncate();
        DB::table('sublinea')->truncate();
        DB::table('marca')->truncate();
        DB::table('modelo')->truncate();
        DB::table('usuario')->truncate();
        DB::table('tecnico')->truncate();
        DB::table('cliente')->truncate();
        DB::table('sucursal')->truncate();
        DB::table('contacto')->truncate();
        DB::table('servicio')->truncate();
        DB::table('tecnico_servicio')->truncate();
        DB::table('tarea')->truncate();
        DB::table('dispositivo')->truncate();
        DB::table('repuesto')->truncate();
        DB::table('compatibilidad_repuesto')->truncate();
        DB::table('ot')->truncate();
        DB::table('dispositivo_ot')->truncate();
        DB::table('accesorio_dispositivo_ot')->truncate();
        DB::table('detalle_dispositivo_ot')->truncate();
        DB::table('tarea_dispositivo')->truncate();
        DB::table('contacto_ot')->truncate();
        DB::table('equipo_tecnico')->truncate();
        DB::table('avance')->truncate();
        DB::table('actividad_extra')->truncate();
        DB::table('notificacion')->truncate();
        DB::table('tarea_ot')->truncate(); //Esta tabla no esta en el cuaderno, cuidado.
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        //Insertando datos en las tablas
        $tipos = [
            [
                'descripcion_tipo_ot' => 'Laboratorio'
            ],
            [
                'descripcion_tipo_ot' => 'Terreno'
            ],
            [
                'descripcion_tipo_ot' => 'Remota'
            ]
        ];

        $prioridades = [
            [
                'descripcion_prioridad_ot' => 'Baja'
            ],
            [
                'descripcion_prioridad_ot' => 'Media'
            ],
            [
                'descripcion_prioridad_ot' => 'Alta'
            ]
        ];

        $estados = [
            [
                'descripcion_estado_ot' => 'Iniciada'
            ],
            [
                'descripcion_estado_ot' => 'Pendiente'
            ],
            [
                'descripcion_estado_ot' => 'Finalizada'
            ]
        ];

        $tipoVisitas = [
            [
                'descripcion_tipo_visita' => 'Presencial'
            ],
            [
                'descripcion_tipo_visita' => 'Emergencia'
            ],
            [
                'descripcion_tipo_visita' => 'Soporte Remoto'
            ],
            [
                'descripcion_tipo_visita' => 'Horas Técnicas'
            ],
            [
                'descripcion.tipo_visita' => 'Otra'
            ]
        ];

        $tiposServicios = [
            [
                'descripcion_tipo_servicio' => 'No requiere dispositivo'
            ],
            [
                'descripcion_tipo_servicio' => 'Requiere dispositivo'
            ]
        ];
        //Datos temporales de categorias
        $categorias = [
            [
                'id' => 1,
                'nombre_categoria' => 'Tecnologia'
            ],
            [
                'id' => 2,
                'nombre_categoria' => 'Tecnologia 2'
            ],
            [
                'id' => 3,
                'nombre_categoria' => 'Tecnologia 3'
            ],
            [
                'id' => 4,
                'nombre_categoria' => 'Tecnologia 4'
            ],
            [
                'id' => 5,
                'nombre_categoria' => 'Tecnologia 5'
            ],
            [
                'id' => 99,
                'nombre_categoria' => 'Otros'
            ],
        ];

        $subcategorias = [
            [
                'id' => 1,
                'nombre_subcategoria' => 'Sub Tecnologia 1',
                'cod_categoria' => 1
            ],
            [
                'id' => 2,
                'nombre_subcategoria' => 'Sub Tecnologia 2',
                'cod_categoria' => 2
            ],
            [
                'id' => 3,
                'nombre_subcategoria' => 'Sub Tecnologia 3',
                'cod_categoria' => 3
            ],
            [
                'id' => 4,
                'nombre_subcategoria' => 'Sub Tecnologia 4',
                'cod_categoria' => 4
            ],
            [
                'id' => 5,
                'nombre_subcategoria' => 'Sub Tecnologia 5',
                'cod_categoria' => 5
            ],
            [
                'id' => 99,
                'nombre_subcategoria' => 'Otros',
                'cod_categoria' => 99
            ],
        ];

        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'guard_name' => 'web',
                'description' => 'Todos los permisos',
                'created_at' => '2024-07-01 15:56:13',
                'updated_at' => '2024-07-01 16:29:26',
                'color' => '#cc6633'
            ],
            [
                'id' => 11,
                'name' => 'Tecnicos',
                'guard_name' => 'web',
                'description' => 'Permisos para los tecnicos',
                'created_at' => '2024-07-01 16:30:28',
                'updated_at' => '2024-07-01 16:30:28',
                'color' => '#00ccff'
            ],
            [
                'id' => 12,
                'name' => 'Clientes',
                'guard_name' => 'web',
                'description' => 'Solo ver',
                'created_at' => '2024-07-01 17:05:39',
                'updated_at' => '2024-07-01 17:13:32',
                'color' => '#1e00ff'
            ]
        ];

        $permisos = [
            [
                'id' => 8,
                'name' => 'ordenes.index',
                'description' => 'Ver el modulo de órdenes',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 9,
                'name' => 'ordenes.create',
                'description' => 'Crear órdenes',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 10,
                'name' => 'ordenes.edit',
                'description' => 'Editar órdenes',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 11,
                'name' => 'roles.index',
                'description' => 'Ver el modulo de roles',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 12,
                'name' => 'roles.create',
                'description' => 'Crear roles',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 13,
                'name' => 'roles.edit',
                'description' => 'Editar roles',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 14,
                'name' => 'roles.destroy',
                'description' => 'Eliminar roles',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 15,
                'name' => 'clientes.index',
                'description' => 'Ver el modulo de clientes',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 16,
                'name' => 'clientes.create',
                'description' => 'Crear clientes',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 17,
                'name' => 'usuarios.index',
                'description' => 'Ver el modulo de usuarios',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 18,
                'name' => 'usuarios.create',
                'description' => 'Crear usuarios',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 19,
                'name' => 'usuarios.edit',
                'description' => 'Editar usuarios',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 20,
                'name' => 'usuarios.update',
                'description' => 'Actualizar usuarios',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ],
            [
                'id' => 21,
                'name' => 'usuarios.destroy',
                'description' => 'Eliminar usuarios',
                'guard_name' => 'web',
                'created_at' => '2024-07-01 19:45:34',
                'updated_at' => '2024-07-01 19:45:34'
            ]
        ];

        $rolesPermisos = [
            [
                'permission_id' => 8,
                'role_id' => 1
            ],
            [
                'permission_id' => 9,
                'role_id' => 1
            ],
            [
                'permission_id' => 10,
                'role_id' => 1
            ],
            [
                'permission_id' => 11,
                'role_id' => 1
            ],
            [
                'permission_id' => 12,
                'role_id' => 1
            ],
            [
                'permission_id' => 13,
                'role_id' => 1
            ],
            [
                'permission_id' => 14,
                'role_id' => 1
            ],
            [
                'permission_id' => 15,
                'role_id' => 1
            ],
            [
                'permission_id' => 16,
                'role_id' => 1
            ],
            [
                'permission_id' => 17,
                'role_id' => 1
            ],
            [
                'permission_id' => 18,
                'role_id' => 1
            ],
            [
                'permission_id' => 19,
                'role_id' => 1
            ],
            [
                'permission_id' => 20,
                'role_id' => 1
            ],
            [
                'permission_id' => 21,
                'role_id' => 1
            ],
            [
                'permission_id' => 8,
                'role_id' => 12
            ]
        ];

        $modelosRoles = [
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 4
            ],
            [
                'role_id' => 11,
                'model_type' => 'App\Models\User',
                'model_id'  => 4
            ],
            [
                'role_id' => 11,
                'model_type' => 'App\Models\User',
                'model_id'  => 18
            ],
            [
                'role_id' => 12,
                'model_type' => 'App\Models\User',
                'model_id'  => 20
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 21
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 88
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 94
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 95
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 96
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 98
            ],
            [
                'role_id' => 11,
                'model_type' => 'App\Models\User',
                'model_id'  => 98
            ],
            [
                'role_id' => 12,
                'model_type' => 'App\Models\User',
                'model_id'  => 98
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id'  => 99
            ],
            [
                'role_id' => 11,
                'model_type' => 'App\Models\User',
                'model_id'  => 99
            ],
            [
                'role_id' => 12,
                'model_type' => 'App\Models\User',
                'model_id'  => 99
            ],
            [
                'role_id' => 8,
                'model_type' => 'App\Models\User',
                'model_id'  => 5
            ],
            [
                'role_id' => 11,
                'model_type' => 'App\Models\User',
                'model_id'  => 6
            ]
        ];

        $tablas = [
            'tipo_ot' => $tipos,
            'prioridad_ot' => $prioridades,
            'estado_ot' => $estados,
            'tipo_visita' => $tipoVisitas,
            'tipo_servicio' => $tiposServicios,
            'categoria' => $categorias,
            'subcategoria' => $subcategorias,
            'roles' => $roles,
            'permissions' => $permisos,
            'role_has_permissions' => $rolesPermisos,
            'model_has_roles' => $modelosRoles
        ];



        echo "<h1>Comenzando poblado de tablas....</h1> <br>";
        foreach ($tablas as $tabla => $data) {
            try {
                DB::table($tabla)->insert($data);
                echo "Tabla " . $tabla . " poblada correctamente <br>";
            } catch (\Exception $e) {
                echo "Error poblando tabla " . $tabla . ":" . $e->getMessage() . "<br>";
            }
        }
        echo " <h2> Tablas pobladas </h2> <br><hr>";
        echo " <h1> Comenzando migración de datos....</h1> <br>";

        //migrando datos

        // L I N E A
        $LineasAntiguas = DB::table('linea_old')->get();

        $datosLineas = [];

        foreach ($LineasAntiguas as $linea_old) {
            $datosLineas[] = [
                'id' => $linea_old->id,
                'nombre_linea' => $linea_old->name,
                'cod_subcategoria' => 1
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosLineas, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('linea')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de linea_old a linea correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de linea_old a linea:" . $e->getMessage() . "<br>";
        }


        // S U B L I N E A
        $SublineasAntiguas = DB::table('sub_linea_old')->get();

        $datosSublineas = [];

        foreach ($SublineasAntiguas as $sublinea_old) {
            $datosSublineas[] = [
                'id' => $sublinea_old->id,
                'nombre_sublinea' => $sublinea_old->name,
                'cod_linea' => $sublinea_old->id_line
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosSublineas, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('sublinea')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de sub_linea_old a sublinea correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de sub_linea_old a sublinea:" . $e->getMessage() . "<br>";
        }

        // M A R C A
        $marcasAntiguas = DB::table('brands_old')->get();

        $datosMarcas = [];

        foreach ($marcasAntiguas as $marca_old) {
            $datosMarcas[] = [
                'id' => $marca_old->id,
                'nombre_marca' => $marca_old->name
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosMarcas, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('marca')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de brands_old a marca correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de brands_old a marca:" . $e->getMessage() . "<br>";
        }


        // M O D E L O

        $modelosAntiguos = DB::table('model_old')->get();

        $datosModelos = [];

        foreach ($modelosAntiguos as $model_old) {
            $datosModelos[] = [
                'id' => $model_old->id,
                'nombre_modelo' => $model_old->name,
                'desc_corta_modelo' => $model_old->short_desc,
                'desc_larga_modelo' => $model_old->long_desc,
                'part_number_modelo' => $model_old->pal_number,
                'cod_marca' => $model_old->id_brand,
                'cod_sublinea' => $model_old->id_sublinea
            ];
        }
        $chunkSize = 100;

        $chunks = array_chunk($datosModelos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('modelo')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de model_old a modelo correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de model_old a modelo:" . $e->getMessage() . "<br>";
        }

        // U S U A R I O

        $usuariosAntiguos = DB::table('users_old')->get();

        $datosUsuarios = [];

        foreach ($usuariosAntiguos as $user_old) {
            $datosUsuarios[] = [
                'id' => $user_old->id,
                'nombre_usuario' => $user_old->name,
                'password_usuario' => $user_old->password,
                'rol_usuario' => $user_old->rol,
                'email_usuario' => $user_old->email
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosUsuarios, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('usuario')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de users_old a usuario correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de users_old a usuario:" . $e->getMessage() . "<br>";
        }


        // T E C N I C O

        $tecnicosAntiguos = DB::table('techs_old')->get();

        $datosTecnicos = [];

        foreach ($tecnicosAntiguos as $techs_old) {
            $datosTecnicos[] = [
                'id' => $techs_old->id,
                'nombre_tecnico' => $techs_old->name,
                'rut_tecnico' => $techs_old->rut,
                'telefono_tecnico' => $techs_old->phone,
                'email_tecnico' => $techs_old->email,
                'precio_hora_tecnico' => $techs_old->hour_price,
                'cod_usuario' => $techs_old->users_id
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTecnicos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('tecnico')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de techs_old a tecnico correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de techs_old a tecnico:" . $e->getMessage() . "<br>";
        }

        // C L I E N T E

        $clientesAntiguos = DB::table('clients_old')->get();

        $datosClientes = [];

        foreach ($clientesAntiguos as $clients_old) {
            $datosClientes[] = [
                'id' => $clients_old->id,
                'nombre_cliente' => $clients_old->name,
                'rut_cliente' => $clients_old->rut,
                'web_cliente' => $clients_old->web,
                'telefono_cliente' => $clients_old->phone,
                'email_cliente' => $clients_old->email
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosClientes, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('cliente')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de clients_old a cliente correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de clients_old a cliente:" . $e->getMessage() . "<br>";
        }


        // S U C U R S A L

        $sucursalesAntiguAs = DB::table('branches_old')->get();

        $datosSucursales = [];

        foreach ($sucursalesAntiguAs as $branches_old) {
            $datosSucursales[] = [
                'id' => $branches_old->id,
                'nombre_sucursal' => $branches_old->branch_name,
                'telefono_sucursal' => $branches_old->phone,
                'direccion_sucursal' => $branches_old->location,
                'cod_cliente' => $branches_old->id_clients
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosSucursales, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('sucursal')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de branches_old a sucursal correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de branches_old a sucursal:" . $e->getMessage() . "<br>";
        }


        //  C O N T A C T O

        $contactosAntiguos = DB::table('contacts_old')->get();

        $datosContactos = [];

        foreach ($contactosAntiguos as $contacts_old) {
            $datosContactos[] = [
                'id' => $contacts_old->id,
                'nombre_contacto' => $contacts_old->contact_name,
                'telefono_contacto' => $contacts_old->phone,
                'departamento_contacto' => $contacts_old->department,
                'cargo_contacto' => $contacts_old->charge,
                'email_contacto' => $contacts_old->email,
                'cod_sucursal' => $contacts_old->id_branches
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosContactos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('contacto')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de contacts_old a contacto correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de contacts_old a contacto:" . $e->getMessage() . "<br>";
        }


        // S E R V I C I O S

        $serviciosAntiguos = DB::table('services_old')->get();

        $datosServicios = [];

        foreach ($serviciosAntiguos as $services_old) {

            $tipoS = 0;

            switch ($services_old->equipment) {
                case 0:
                    $tipoS = 1;
                    break;

                case 1:
                    $tipoS = 2;
                    break;

                case 2:
                    $tipoS = 2;
                    break;
                default:
                    $tipoS = 1;
                    break;
            }
            $datosServicios[] = [
                'id' => $services_old->id,
                'nombre_servicio' => $services_old->service_name,
                'cod_tipo_servicio' => $tipoS,
                'cod_sublinea' => $services_old->id_sublinea
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosServicios, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('servicio')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de services_old a servicio correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de services_old a servicio:" . $e->getMessage() . "<br>";
        }


        // T E C N I C O  - S E R V I C I O

        $tecnicosServicioAntiguo = DB::table('techs_services_old')->get();

        $datosTecnicosServicio = [];

        foreach ($tecnicosServicioAntiguo as $techs_services_old) {

            $datosTecnicosServicio[] = [
                'id' => $techs_services_old->id_relation,
                'cod_servicio' => $techs_services_old->id_service,
                'cod_tecnico' => $techs_services_old->id_tech
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTecnicosServicio, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('tecnico_servicio')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de techs_services_old a tecnico_servicio correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de techs_services_old a tecnico_servicio:" . $e->getMessage() . "<br>";
        }

        // T A R E A S

        $tareasAntiguas = DB::table('tasks_old')->get();

        $datosTareas = [];

        foreach ($tareasAntiguas as $tasks_old) {

            $datosTareas[] = [
                'id' => $tasks_old->id,
                'nombre_tarea' => $tasks_old->name,
                'tiempo_tarea' => $tasks_old->tiempo,
                'cod_servicio' => $tasks_old->id_service
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTareas, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('tarea')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de tasks_old a tarea correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de tasks_old a tarea:" . $e->getMessage() . "<br>";
        }


        // D I S P O S I T I V O

        $dispositivosAntiguos = DB::table('equipment_old')->get();

        $datosDispositivos = [];

        foreach ($dispositivosAntiguos as $equipment_old) {

            $datosDispositivos[] = [
                'id' => $equipment_old->id,
                'numero_serie_dispositivo' => $equipment_old->series_number,
                'cod_modelo' => $equipment_old->id_model,
                'cod_sucursal' => $equipment_old->id_branches
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosDispositivos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('dispositivo')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de equipment_old a dispositivo correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de equipment_old a dispositivo:" . $e->getMessage() . "<br>";
        }



        // O R D E N   D E   T R A B A J O

        //Se crea un técnico que se usara por defecto cuando no se encuentre el técnico requerido:
        DB::table('tecnico')->insert([
            'id' => 9999,
            'nombre_tecnico' => 'No Disponible',
            'rut_tecnico' => 'completar',
            'telefono_tecnico' => 'completar',
            'email_tecnico' => 'completar',
            'precio_hora_tecnico' => 0
        ]);

        $otAntiguas = DB::table('ots_old')->get();

        $datosOt = [];

        foreach ($otAntiguas as $ots_old) {

            $tipoOt = 0;

            switch (strtolower($ots_old->type)) {
                case 'laboratorio':
                    $tipoOt = 1;
                    break;
                case 'terreno':
                    $tipoOt = 2;
                    break;
                case 'remota':
                    $tipoOt = 3;
                    break;
                default:
                    $tipoOt = 0;
                    break;
            }

            $prioridadOt = 0;

            switch (strtolower($ots_old->priority)) {
                case 'baja':
                    $prioridadOt = 1;
                    break;
                case 'media':
                    $prioridadOt = 2;
                    break;
                case 'alta':
                    $prioridadOt = 3;
                    break;
                default:
                    $prioridadOt = 0;
                    break;
            }

            $estadoOt = 0;

            switch (strtolower($ots_old->status)) {
                case 'iniciada':
                    $estadoOt = 1;
                    break;
                case 'pendiente':
                    $estadoOt = 2;
                    break;
                case 'finalizada':
                    $estadoOt = 3;
                    break;
                default:
                    $estadoOt = 0;
                    break;
            }


            $tipoVisitaOt = 5;

            switch (strtolower($ots_old->tipo_visitas)) {
                case 'presencial':
                    $tipoVisitaOt = 1;
                    break;
                case 'emergencia':
                    $tipoVisitaOt = 2;
                    break;
                case 'soporte remoto':
                    $tipoVisitaOt = 3;
                    break;
                case 'horas tecnicas':
                    $tipoVisitaOt = 4;
                    break;
                default:
                    $tipoVisitaOt = 5;
                    break;
            }

            $tecnicoEncargado = DB::table('tecnico')->where('nombre_tecnico', $ots_old->leader)->first();

            if ($tecnicoEncargado) {
                $cod_tecnico_encargado = $tecnicoEncargado->id;
            } else {
                $cod_tecnico_encargado = 9999;
            }


            $datosOt[] = [
                'numero_ot' => $ots_old->number,
                'horas_ot' => $ots_old->hours,
                'descripcion_ot' => $ots_old->description,
                'comentario_ot' => $ots_old->comment,
                'cotizacion' => $ots_old->cotizacion,
                'cod_tipo_ot' => $tipoOt,
                'cod_prioridad_ot' => $prioridadOt,
                'cod_estado_ot' => $estadoOt,
                'cod_tipo_visita' => $tipoVisitaOt,
                'cod_servicio' => $ots_old->id_service,
                'cod_contacto' => $ots_old->id_contact,
                'cod_tecnico_encargado' => $cod_tecnico_encargado,
                'created_at' => $ots_old->created_at
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosOt, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de ots_old a ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de ots_old a ot:" . $e->getMessage() . "<br>";
        }

        // D I S P O S I T I V O -  O T

        $datosTasksEquipments = [];

        $tasksEquipmentsOld = DB::table('tasks_equipments_old')
            ->select('ID_EQUIPMENT', 'NUMBER_OT')
            ->where('ID_EQUIPMENT', '!=', 0)
            ->distinct()
            ->get();

        foreach ($tasksEquipmentsOld as $taskEquipmentOld) {
            $datosTasksEquipments[] = [
                'cod_dispositivo' => $taskEquipmentOld->ID_EQUIPMENT,
                'cod_ot' => $taskEquipmentOld->NUMBER_OT,
            ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTasksEquipments, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('dispositivo_ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de tasks_equipments_old a dispositivo_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de tasks_equipments_old a dispositivo_ot:" . $e->getMessage() . "<br>";
        }

        // A C C E S O R I O -  D I S P O S I T I V O -  O T

        $dispositivos_ot = DB::table('dispositivo_ot')->get();

        $datosAccesorios = [];

        foreach ($dispositivos_ot as $dispositivo_ot) {
            $ot = DB::table('ots_old')->where('number', $dispositivo_ot->cod_ot)->first();

            if ($ot->cargador) {
                $cargador = $ot->cargador;
            } else {
                $cargador = 'No Aplica';
            }

            if ($ot->cable) {
                $cable = $ot->cable;
            } else {
                $cable = 'No Aplica';
            }

            if ($ot->adaptador) {
                $adaptador = $ot->adaptador;
            } else {
                $adaptador = 'No Aplica';
            }

            if ($ot->bateria) {
                $bateria = $ot->bateria;
            } else {
                $bateria = 'No Aplica';
            }

            if ($ot->pantalla) {
                $pantalla = $ot->pantalla;
            } else {
                $pantalla = 'No Aplica';
            }

            if ($ot->teclado) {
                $teclado = $ot->teclado;
            } else {
                $teclado = 'No Aplica';
            }

            if ($ot->drum) {
                $drum = $ot->drum;
            } else {
                $drum = 'No Aplica';
            }

            if ($ot->toner) {
                $toner = $ot->toner;
            } else {
                $toner = 'No Aplica';
            }

            if ($ot->accesorios != 0) {
                $datosAccesorios[] =
                    [
                        'cargador_acc' => $cargador,
                        'cable_acc' => $cable,
                        'adaptador_acc' => $adaptador,
                        'bateria_acc' => $bateria,
                        'pantalla_acc' => $pantalla,
                        'teclado_acc' => $teclado,
                        'drum_acc' => $drum,
                        'toner_acc' => $toner,
                        'cod_dispositivo_ot' => $dispositivo_ot->id
                    ];
            }
        }


        $chunkSize = 100;

        $chunks = array_chunk($datosAccesorios, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('accesorio_dispositivo_ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de ots_old, sección de accesorios, a accesorio_dispositivo_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de ots_old, sección de accesorios, a accesorio_dispositivo_ot:" . $e->getMessage() . "<br>";
        }

        // D E T A L L E -  D I S P O S I T I V O -  O T

        $dispositivos_ot = DB::table('dispositivo_ot')->get();

        $datosDetalles = [];

        foreach ($dispositivos_ot as $dispositivo_ot) {
            $ot = DB::table('ots_old')->where('number', $dispositivo_ot->cod_ot)->first();

            if ($ot->rayones) {
                $rayones = $ot->rayones;
            } else {
                $rayones = 'No Aplica';
            }

            if ($ot->rupturas) {
                $rupturas = $ot->rupturas;
            } else {
                $rupturas = 'No Aplica';
            }

            if ($ot->tornillos) {
                $tornillos = $ot->tornillos;
            } else {
                $tornillos = 'No Aplica';
            }

            if ($ot->gomas) {
                $gomas = $ot->gomas;
            } else {
                $gomas = 'No Aplica';
            }

            if ($ot->estado) {
                $estado = $ot->estado;
            } else {
                $estado = 'No Aplica';
            }

            if ($ot->observaciones) {
                $observaciones = $ot->observaciones;
            } else {
                $observaciones = 'No Aplica';
            }
            if ($ot->detalle != 0) {
                $datosDetalles[] =
                    [
                        'rayones_det' => $rayones,
                        'rupturas_det' => $rupturas,
                        'tornillos_det' => $tornillos,
                        'gomas_det' => $gomas,
                        'estado_dispositivo_det' => $estado,
                        'observaciones_det' => $observaciones,
                        'cod_dispositivo_ot' => $dispositivo_ot->id
                    ];
            }
        }


        $chunkSize = 100;

        $chunks = array_chunk($datosDetalles, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('detalle_dispositivo_ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de ots_old, sección de detalles, a detalle_dispositivo_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de ots_old, sección de detalles, a detalle_dispositivo_ot:" . $e->getMessage() . "<br>";
        }


        //  T A R E A - D I S P O S I T I V O


        $datosTareasDispositivos = [];

        foreach ($dispositivos_ot as $dispositivo_ot) {
            $tareasDis = DB::table('tasks_equipments_old')->where('number_ot', $dispositivo_ot->cod_ot)->where('id_equipment', $dispositivo_ot->cod_dispositivo)->get();

            foreach ($tareasDis as $tareaDis) {
                $datosTareasDispositivos[] =
                    [
                        'cod_tarea' => $tareaDis->id_tasks,
                        'cod_dispositivo_ot' => $dispositivo_ot->id
                    ];
            }
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTareasDispositivos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('tarea_dispositivo')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de ots_old, sección de tareas, a tarea_dispositivo_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de ots_old, sección de tareas, a tarea_dispositivo_ot:" . $e->getMessage() . "<br>";
        }

        // T A R E A - O T

        $datosTareasOT = [];

        $tareasOtAntiguas = DB::table('tasks_equipments_old')->where('id_equipment', 0)->get();

        foreach ($tareasOtAntiguas as $tareaOtAntigua) {

            $datosTareasOT[] =
                [
                    'cod_tarea' => $tareaOtAntigua->id_tasks,
                    'cod_ot' => $tareaOtAntigua->number_ot
                ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosTareasOT, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('tarea_ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de tasks_equipments_old que no requerian equipos  a tarea_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de tasks_equipments_old que no requerian equipos  a tarea_ot" . $e->getMessage() . "<br>";
        }


        // C O N T A C T O - O T

        $contactosOtAntiguos = DB::table('participants_contacts_old')->get();

        $datosContactosOt = [];

        foreach ($contactosOtAntiguos as $contactoOtAntiguo) {
            $datosContactosOt[] =
                [
                    'cod_contacto' => $contactoOtAntiguo->id_contact,
                    'cod_ot' => $contactoOtAntiguo->ot_number
                ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosContactosOt, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('contacto_ot')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de participants_contacts_old a contacto_ot correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de participants_contacts_old a contacto_ot" . $e->getMessage() . "<br>";
        }


        // E Q U I P O - T E C N I C O
        //codigo tecnico defecto 9999
        $equiposTecnicosAntiguos = DB::table('participants_old')->get();

        $datosEquiposTecnicos = [];

        foreach ($equiposTecnicosAntiguos as $equipoTecnicoAntiguo) {

            $tecnicoEquipo = DB::table('tecnico')->where('nombre_tecnico', $equipoTecnicoAntiguo->participant_name)->first();

            if ($tecnicoEquipo) {
                $cod_tecnico_encargado = $tecnicoEquipo->id;
            } else {
                $cod_tecnico_encargado = 9999;
            }


            $datosEquiposTecnicos[] =
                [
                    'cod_tecnico' => $cod_tecnico_encargado,
                    'cod_ot' => $equipoTecnicoAntiguo->ot_number
                ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosEquiposTecnicos, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('equipo_tecnico')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de participants_old a equipo_tecnico correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de participants_old a equipo_tecnico" . $e->getMessage() . "<br>";
        }


        // A V A N C E S


        $avancesAntiguos = DB::table('advances_old')->get();

        $datosAvances = [];

        foreach ($avancesAntiguos as $avanceAntiguo) {

            if ($avanceAntiguo->created_at == "0000-00-00 00:00:00") {
                $fecha = null;
            } else {
                $fecha = $avanceAntiguo->created_at;
            }
            $datosAvances[] =
                [
                    'comentario_avance' => $avanceAntiguo->comment,
                    'fecha_avance' => $fecha,
                    'tiempo_avance' => $avanceAntiguo->time_spent,
                    'cod_ot' => $avanceAntiguo->ot_number
                ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosAvances, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('avance')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de advances_old a avance correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de advances_old a avance" . $e->getMessage() . "<br>";
        }

        // A C T I V I D A D E S -  E X T R A


        $actividadesExtraAntiguas = DB::table('activities_old')->get();

        $datosActividadesExtra = [];

        foreach ($actividadesExtraAntiguas as $actividadExtraAntigua) {

            /*
            if ($actividadExtraAntigua->created_at == "0000-00-00 00:00:00") {
                $fecha = null;
            } else {
                $fecha = $actividadExtraAntigua->created_at;
            }
            */
            $numero_otAntigua = DB::table('ots_old')->where('id', $actividadExtraAntigua->id_ot)->first();


            $datosActividadesExtra[] =
                [
                    'nombre_actividad' => $actividadExtraAntigua->name,
                    'horas_actividad' => $actividadExtraAntigua->hours,
                    'cod_ot' => $numero_otAntigua->number
                ];
        }

        $chunkSize = 100;

        $chunks = array_chunk($datosActividadesExtra, $chunkSize);

        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('actividad_extra')->insert($chunk);
            }

            DB::commit();
            echo "Datos migrados de activities_old a actividad_extra correctamente <br>";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error migrando datos de activities_old a actividad_extra" . $e->getMessage() . "<br>";
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        return view('migrar');
    }
}
