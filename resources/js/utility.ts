/**
 * Clase de utilidad general
 *
 * @author Jhoan Sebastian Chilito S. - May. 28 - 2020
 * @version 1.0.0
 */
export default class Utility {

    /**
     * Clona los elementos ingresados
     *
     * @author Jhoan Sebastian Chilito S. - May. 28 - 2020
     * @version 1.0.0
     *
     * @param dataToClone datos a clonar
     */
    public static clone(dataToClone: any): any {
        return JSON.parse(JSON.stringify(dataToClone));
    }
}
