export function formattedDate(date: string): string {
    return new Intl.DateTimeFormat('pt-BR', {
        timeZone: 'utc'
    }).format(new Date(date));
}
