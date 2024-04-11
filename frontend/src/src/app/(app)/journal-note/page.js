"use client"

import { Box, TextareaAutosize, Button , Card, CardContent, Modal, Input, CardHeader, CardTitle,CardDescription,Checkbox, Typography} from '@mui/material';
import { useEffect, useState } from 'react';
import laravelAxios from '@/lib/laravelAxios';
import ToDoList from '@/components/ToDoList';
import formatDate from '@/components/formatDate';



export default function note(){

    // 現在の日付の状態を管理します（初期値として現在の日付を設定）
  const [currentDate, setCurrentDate] = useState(new Date());
  const [journalEntries, setJournalEntries] = useState([
        { id: 0, content: "" },
        { id: 1, content: "" },
        { id: 2, content: "" }
    ]);
    const journalDate = currentDate;
    const [feedback, setFeedback] = useState([]);
    const [isModalOpen, setIsModalOpen] = useState(null);
    const titles= [
        ["今日1日の分析 したこと、できなかったこと、その理由など"],
        ["今の感情、悩み、考えていること、やりたいこと"],
        ["理想の自分、生活"]
    ]
    
    //フィードバックを取得する
    useEffect(() => {
        if (!journalDate) return;
        const fetchFeedback = async () => {
            try {
                const response = await laravelAxios.get(`api/journal-date/${formatDate(currentDate)}/reflection`);
                // レスポンスデータが空かどうかを確認
                if (response.data.length === 0) {
                    setFeedback([""]);

                } else {
                    // フィードバックがnullでない要素だけをフィルタリング
                    const filteredFeedback = response.data.filter(item => item.feedback != null);
                    // フィルタリングされたフィードバックが存在するかどうかを確認
                    if (filteredFeedback.length > 0) {
                        console.log('feedback exists');
                        console.log(response.data)
                        setFeedback(filteredFeedback);
                    } else {
                        setFeedback([""]);
                    }
                }
            } catch (err) {
                console.log(err);
            }
        };
        fetchFeedback();
    }, [journalDate]);
    
    //AIフィードバック送信
    const handleAiFeedback = async () => {
        try {
            const response = await laravelAxios.get(`api/journal-date/${formatDate(currentDate)}/feedback`);
            console.log('Journal added', response.data);
        } catch(err) {
            console.log('Error adding journal', err);
        }
    };
   //ジャーナルを取得する
    useEffect(() => {
        if(!journalDate) return;
        const fetchJournalDetail = async() => {
            try {
                const response = await laravelAxios.get(`api/journal-date/${formatDate(currentDate)}`);
                setJournalEntries([
                    { id: 0, content: "" },
                    { id: 1, content: "" },
                    { id: 2, content: "" }
                ]);

                if (response.data.length === 0) {
                    
                    console.log("test空");
                    console.log(response);
                } else {
                    const updatedEntries = journalEntries.map(entry => {
                        // 応答データから同じid(type)を持つエントリーを見つける
                        const matchingEntry = response.data.find(item => item.type === entry.id);
                        // 見つかった場合、contentを更新。見つからなければ元のエントリーをそのまま返す
                        return matchingEntry ? { ...entry, content: matchingEntry.content } : entry;
                    });
                    
                    setJournalEntries(updatedEntries);    
                }
                
            } catch(err) {
                console.log(err);
            }
        };
        fetchJournalDetail();
    }, [journalDate]);

    //モーダル開け閉めの関数
    const handleOpen = (id) =>{
        setIsModalOpen(id)

    }
    const handleClose = () =>{
        setIsModalOpen(null)
    }
    

  //ジャーナル記入画面
    const renderJournalEntries = () => journalEntries.map(entry => (
        
        <div key={entry.id}>
            <Card>
            <CardContent className="flex items-start gap-4  p-6 bg-white " onClick={() => handleOpen(entry.id)}>
              <div className="grid text-black bg-white font-mono" style={{ width: '100%', height:'100%'}}>
                <h2 className="text-xl font-bold">{titles[entry.id]}</h2>
                <TextareaAutosize style={{  border:'none', width: '100%', height:'100px'}} className="text-sm leading-none h-10 text-black bg-white"
                defaultValue={entry.content}
                />
              </div>
            </CardContent>
        </Card>
        {/* modal window */}
        <Modal open={isModalOpen === entry.id} onClose={handleClose}>
            
            <Box
                sx={{
                    position:'absolute',
                    top:'50%',
                    left:'50%',
                    transform: "translate(-50%, -50%)",
                    width: 800,
                    height:400,
                    bgcolor: "Background.paper",
                    border: "2px solid, #000",
                    boxShadow:24,
                    p:4,
                }}
            >
                <TextareaAutosize
                    required
                    minRows={5}
                    placeholder='content'
                    defaultValue={journalEntries[entry.id].content}
                    style={{width:"100%" ,height:"200", marginTop:"10px"}}
                    
                    onChange={(e) => handleContentChange(entry.id, e.target.value)}
                />
                <Button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" onClick={() => handleAddJournal(entry.id)}>Done</Button>
            </Box>
            
        </Modal>
        </div>
        
    ));

    const handleContentChange = (id, content) => {
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
//ジャーナルをデータベースに追加する
    const handleAddJournal = async (id) => {
        const entry = journalEntries.find(entry => entry.id === id);
        try {
            // サーバーに送信するデータに `id` を含める
            const response = await laravelAxios.post('api/journal-date/update', {
                date: formatDate(journalDate),
                id: entry.id, 
                content: entry.content
            });
            console.log(entry);
            console.log('success');
            console.log(response);  
            console.log('Journal added', response.data);
            handleClose();
        } catch (err) {
            console.log('Error adding journal', err);
        }
    };

  // 次の日ボタン
  const handleNextDay = () => {
    const nextDay = new Date(currentDate);
    nextDay.setDate(nextDay.getDate() + 1);
    setCurrentDate(nextDay);
    
  };
  //前の日ボタン
  const handlePrevDay = () => {
    const previousDay = new Date(currentDate);
    previousDay.setDate(previousDay.getDate() - 1);
    setCurrentDate(previousDay);
    
  };

  

  return (
    <>
        <div className="px-4 py-6" style={{backgroundColor:'MediumPurple'}}>
            <div className="space-y-1">
                <h1 className="font-bold tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-500 text-6xl">A Day in the Life : {formatDate(currentDate)}</h1>
            </div>
            <div className="grid grid-cols-5 gap-4">
                {/* 右側のコンテンツ (ToDoリストを2分の1のスペースに配置) */}
                <div className="col-span-2 mt-8">
                {/* ToDoリストのコンポーネントや内容をここに挿入 */}
                    <ToDoList date={formatDate(currentDate)}/>
                </div>
                {/* 左側のコンテンツ (現在の内容を3分の1のスペースに配置) */}
                <div className="col-span-3 space-y-4"> 
                    <div className="grid gap-4 mt-3">
                        {renderJournalEntries()}
                    </div>
                </div>
            </div>
            <div className="space-y-2 mb-3">
                <h2 className="font-bold tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-500 text-2xl">AI Feedback</h2>
                <div className='text-black bg-white p-2 '>
                    {feedback.map((item, index) => (
                    <div key={index} className='text-black bg-white p-2 '>{item.feedback}</div> // `feedback`プロパティの値を直接レンダリング
                    ))}
                </div>
            </div>
            <div>
                <Button variant="contained" onClick={handleAiFeedback}>Get AI Feedback</Button>
            </div>
            <div className="inline-flex mt-4">
            
                <button  variant="contained" className="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l" onClick={handlePrevDay}>
                Previous day
                </button>
                <button variant="contained" className="bg-gray-300 ml-2 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r" onClick={handleNextDay}>
                    Next day  
                </button>
            </div>
        </div>
            
    </>
  )
}