import { Button , Card, CardContent, Input, CardHeader,  Checkbox, Typography} from '@mui/material';
import { useEffect, useState } from 'react';
import laravelAxios from '@/lib/laravelAxios';
import TrashIcon from '@/components/TrashIcon';
import PlusIcon from '@/components/PlusIcon';


const ToDoList = (props) => {
    
    const [task, setTask] = useState([]);
    const [text, setText] = useState("");

    const onChangeText = (e) => {
    setText(e.target.value);
    }

    //taskを取得する
    useEffect(() => {
        if(!props.date) return;
        const fetchTasks = async() => {
            setTask([]);
            try {
                const response = await laravelAxios.get(`api/journal-date/${props.date}/task`);
                
                if (response.data.length === 0) {                    
                    console.log(response);
                } else {
                    setTask(response.data);    
                }
                
            } catch(err) {
                console.log(err);
            }
        };
        fetchTasks();
    }, [props.date]);


    //taskを追加する
    const handleAddTask = async () => {
        if (!text) return; // 空のテキストを追加しない
        try {
            const response = await laravelAxios.post('api/journal-date/insert-task', {
                date: props.date,
                title: text
            });
            setTask(prevTasks => [...prevTasks, response.data]); // 新しいタスクを追加
            setText(''); // テキストフィールドをクリア
        } catch (err) {
            console.error('Error adding task:', err);
        }
    };

    //taskを消去する
    const handleDeleteTask = async (taskId) => {
        try {
            await laravelAxios.delete(`api/journal-date/delete-task/${taskId}`);
            // リストからタスクを消す
            setTask(prevTasks => prevTasks.filter(task => task.id !== taskId));
        } catch (err) {
            console.error('Error deleting task:', err);
        }
    };
    
    // //taskをcheckする
    //  const handleCheckTask = async (taskId) => {
    //     try {
    //         await laravelAxios.delete(`api/journal-date/check-task/${taskId}`);
    //         // Delete successful, remove the task from the state
    //         setTask(prevTasks => prevTasks.filter(task => task.id !== taskId));
    //     } catch (err) {
    //         console.error('Error deleting task:', err);
    //     }
    // };

    return (

            <Card className="w-full max-w-lg mx-auto">
                <div className="text-2xl font-bold pl-3 mt-2">今日やるべきこと</div>
            <CardContent>
                <form className="flex gap-2">
                    <Input className="flex-1 min-w-0" placeholder="Enter a new task" value={text} onChange={onChangeText} type="text" />
                    <Button size="icon" onClick={handleAddTask}>
                    <PlusIcon className="h-4 w-4" />
                    <span className="sr-only" >Add</span>
                    </Button>
                </form>
                <ul className="list-disc pl-5 mt-4">
                    {task.map((item, index) => (
                        <li className="flex items-center gap-2" key={`${item.id}-${index}`}>
                            <Checkbox id={`task-${item.id}-${index}`} />
                            <label className="text-m font-semibold cursor-pointer" htmlFor={`task-${item.id}-${index}`}>{item.title}</label> 
                            <Button className="ml-auto" size="icon" onClick={() => handleDeleteTask(item.id)}>
                                <TrashIcon className="h-4 w-4" />
                                <span className="sr-only">Delete</span>
                            </Button>
                        </li>
                    ))}
                </ul>
                </CardContent>
            </Card>
        
    )
}

export default ToDoList
