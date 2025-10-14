import IPosition from "../position/IPosition";
import IDependency from "../dependency/IDependency";

/**
 * Tipos de dato para el modelo Usuario
 */
export default interface IUser {

    /**
     * Id del usuario
     */
    id: number;
    /**
     * Id del cargo
     */
    id_cargo: number;
    /**
     * Id de la dependencia
     */
    id_dependencia: number;
    /**
     * Nombre completo
     */
    name: string;
    /**
     * Correo electronico
     */
    email: string;
    /**
     * Direccion de la imagen de perfil
     */
    url_img_profile: string;
    /**
     * Direccion de la firma digital
     */
    url_digital_signature: string;
    /**
     * Nombre de usuario
     */
    username: number;
    /**
     * Valida si el usuario esta bloqueado
     */
    block: boolean;
    /**
     * Valida si el usuario esta dispuesto a recibir notificaciones al correo
     */
    sendEmail: boolean;
    /**
     * Fecha de creacion
     */
    created_at: Date | string;
    /**
     * Datos del cargo
     */
    positions: IPosition;
    /**
     * Datos de la dependencia
     */
    dependencies: IDependency;
    /**
     * Lista de roles asignados
     */
    roles: Array<any>;
    /**
     * Lista de grupos de trabajo a los que pertenece
     */
    work_groups: Array<any>;

}
