const handleContentChange = (id, content) => {

    const [journalEntries, setJournalEntries] = useState([
        { id: 0, content: "" },
        { id: 1, content: "" },
        { id: 2, content: "" }
    ]);
    const updatedEntries = journalEntries.map(entry => {
        if (entry.id === id) {
            return { ...entry, content };
        }
        return entry;
    });
    setJournalEntries(updatedEntries);
    console.log(updatedEntries[0]);
    console.log(updatedEntries[1]);
    console.log(updatedEntries[2]);
};

export default handleContentChange