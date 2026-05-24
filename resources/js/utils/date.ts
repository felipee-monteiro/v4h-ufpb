export function formattedDate(date: string): string {
    const [year, month, day] = date.split('-').map(Number);

    const safeDate = new Date(year, month - 1, day);

    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(safeDate);
}
