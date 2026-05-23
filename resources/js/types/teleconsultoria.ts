export type TeleconsultoriaStatus =
    | 'Pendente'
    | 'Em andamento'
    | 'Concluída'
    | 'Cancelada';

export type Teleconsultoria = {
    id: string;
    patient: string;
    initials: string;
    specialty: string;
    date: string;
    status: TeleconsultoriaStatus;
    diagnostic_hypothesis: string;
    clinical_history: string;
    professional?: string;
    professional_uuid?: string;
    professional_opinion?: string;
};

export type TeleconsultoriaSpecialty = {
    uuid: string;
    title: string;
};

export type TeleconsultoriaDateFilterKey = 'dateFrom' | 'dateTo';

export type TeleconsultoriaFilters = {
    search: string;
    statuses: TeleconsultoriaStatus[];
    dateFrom: string;
    dateTo: string;
    teleconsultorias: Teleconsultoria[];
};
