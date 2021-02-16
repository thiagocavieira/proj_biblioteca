<?php
/**
 * EventoCalendarForm Form
 * @author  <your name here>
 */
class EventoCalendarFormView extends TPage
{
    private $fc;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->enableDays([0,1,2,3,4,5,6]);
        $this->fc->setReloadAction(new TAction(array($this, 'getEvents')));
        $this->fc->setDayClickAction(new TAction(array('EventoCalendarForm', 'onStartEdit')));
        $this->fc->setEventClickAction(new TAction(array('EventoCalendarForm', 'onEdit')));
        $this->fc->setEventUpdateAction(new TAction(array('EventoCalendarForm', 'onUpdateEvent')));
        $this->fc->setCurrentView('month');
        $this->fc->setTimeRange('07:00', '23:00');
        $this->fc->enablePopover('Informações', "Título do evento: {titulo}
 {observacao} ");

        parent::add( $this->fc );
    }

    /**
     * Output events as an json
     */
    public static function getEvents($param=NULL)
    {
        $return = array();
        try
        {
            TTransaction::open('biblioteca');

            $criteria = new TCriteria(); 

            $criteria->add(new TFilter('horario_inicial', '<=', $param['end'].' 23:59:59'));
            $criteria->add(new TFilter('horario_final', '>=', $param['start'].' 00:00:00'));

            $events = Evento::getObjects($criteria);

            if ($events)
            {
                foreach ($events as $event)
                {
                    $event_array = $event->toArray();
                    $event_array['start'] = str_replace( ' ', 'T', $event_array['horario_inicial']);
                    $event_array['end'] = str_replace( ' ', 'T', $event_array['horario_final']);
                    $event_array['id'] = $event->id;
                    $event_array['color'] = $event->render("{cor}");
                    $event_array['title'] = TFullCalendar::renderPopover($event->render("{titulo}"), $event->render("Informações"), $event->render("Título do evento: {titulo}
 {observacao} "));

                    $return[] = $event_array;
                }
            }
            TTransaction::close();
            echo json_encode($return);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Reconfigure the callendar
     */
    public function onReload($param = null)
    {
        if (isset($param['view']))
        {
            $this->fc->setCurrentView($param['view']);
        }

        if (isset($param['date']))
        {
            $this->fc->setCurrentDate($param['date']);
        }
    }

}

