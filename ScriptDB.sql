// Entidad Jornadas
CREATE TABLE Jornada (
	IDjornada int,
	nombre_jornada char (20),
	hora_entrada time,
	hora_salida time,
	PRIMARY KEY (IDjornada))

// Entidad Departamentos
CREATE TABLE Departamento (
	IDdept int,
	nombre_dept char(20),
	PRIMARY KEY (IDdept))

// Entidad Empleados
CREATE TABLE Empleado (
	IDempleado int,
	nombre_empleado char(20),
	IDdept int,
	IDjornada int,
	PRIMARY KEY (IDempleado),
	FOREIGN KEY (IDdept) REFERENCES Departamento,
	FOREIGN KEY (IDjornada) REFERENCES Jornada)

// Entidad Permisos
CREATE TABLE Permiso (
	IDpermiso int,
	IDempleado int,
	fecha_permiso date,
	motivo_falta char(50),
	PRIMARY KEY (IDpermiso),
	UNIQUE (IDempleado, fecha_permiso),
	FOREIGN KEY (IDempleado) REFERENCES Empleado)

// Entidad Marcas
CREATE TABLE Marca (
	IDempleado int,
	tipo_marca char(10),
	fecha DATE DEFAULT CURRENT_DATE,
    	hora Time DEFAULT CURRENT_TIME,
	PRIMARY KEY (IDempleado, tipo_marca, fecha),
	FOREIGN KEY (IDempleado) REFERENCES Empleado)

//Seguridad de la base de datos
CREATE ROLE administrador WITH LOGIN PASSWORD 'codypumpum123';

GRANT ALL ON Empleado TO administrador;
GRANT ALL ON Departamento TO administrador;
GRANT ALL ON Jornada TO administrador;
GRANT ALL ON Permiso TO administrador;
GRANT SELECT ON Marca to administrador;

CREATE ROLE empleado WITH LOGIN PASSWORD 'chamba123';

GRANT INSERT ON Marca TO empleado;