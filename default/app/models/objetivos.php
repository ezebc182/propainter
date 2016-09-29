<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 02/09/2016
 * Time: 05:56 PM
 */
class Objetivos
{
    public function getObjetivosGenerales()
    {
        return $this->objetivos_especificos = (object)
        array(
            1 => "Evaluar, colaborar, ejecutar y rediseñar planes terapéuticos que permitan a sus destinatarios 
            llevar adelante un proceso de rehabilitación acorde a cada necesidad.",
            2 => "Promover y facilitar la integración del Sistema Educativo Formal de aquellos niños que se encuentren 
            en condiciones de hacerlo y que puedan beneficiarse en este proceso.",
            3 => "Promover el acercamiento de la familia al contexto terapéutico.",
            4 => "Generar situaciones sociales de integración e intercambio para todos los pacientes."

        );
    }

    public function getObjetivosEspecificos()
    {
        return $this->objetivos_especificos = (object)
        array(
            1 => "Programar tratamientos dinámicos y flexibles para cada caso en particular a partir 
                de un diagnóstico preciso.",
            2 => "Crear planes de trabajo en forma conjunta con distintos terapeutas.",
            3 => "Brindar atención individual y grupal de acuerdo a las necesidades de cada persona y el 
                tratamiento planteado.",
            4 => "Promover el desarrollo de la autonomía auto valimiento, autodereminación y sociabilidad, 
                favoreciendo la autoestima y desarrollo personal.",
            5 => "Favorecer la interacción y la integración social en los diversos ámbitos de la comunidad.",
            6 => "Trabajar en forma constante y coordinada con la familia, teniendo en cuenta en la elaboración de
                 los planes de tratamiento, el contexto al que pertenece el paciente.",
            7 => "Fomentar la capacitación y actualización de los distintos profesionales de nuestro equipo de 
                rehabilitación.",
            8 => "Realizar ateneos con una frecuencia mensual mínima. Entre los temas que se tratan en las reuniones
                 del equipo se incluyen los siguientes: El plan de cuidados del paciente, los progresos del mismo,
                  los objetivos a corto y largo plazo, las necesidades educativas del paciente y su familia, etc."

        );
    }

    public function getBeneficiarios()
    {
        return $this->beneficiarios = (object)
        array(
            1 => "Pacientes pediátricos, niños y adolescentes con secuelas de Traumatismo Cráneo Encefálico o 
            Traumatismo Raquimedular, lesiones del sistema nervioso central o periférico, enfermedades degenerativas,
             meningitis, parálisis cerebral, mielomeningocele, retraso psicomotor, parálisis faciales, distrofias
              musculares, parálisis obstétricas y todas aquellas patologías que ocasionan desbalances 
              osteomioarticulares. ",
            2 => "Pacientes con desórdenes sensoriales, trastorno generalizado del desarrollo, síndromes genéticos, 
            retraso mental, demencias y todas aquellas alteraciones que lleven a una desviación en su desarrollo 
            general.");
    }

}