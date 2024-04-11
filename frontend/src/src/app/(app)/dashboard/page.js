"use client"

import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin, { Draggable, DropArg } from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';


const Dashboard = () => {

  return (
    <>
        <div className="py-4">
            <div className="mx-auto max-w-6xl"> {/* Adjust container width */}
                <div className="bg-white overflow-hidden shadow-sm ">
                    <div className="p-6 bg-white border-b border-gray-200">
                    <main className="flex flex-col items-center justify-between p-8">
                        <FullCalendar
                          plugins={[
                            dayGridPlugin,
                            interactionPlugin,
                            timeGridPlugin
                          ]}
                          headerToolbar={{
                            left: 'prev,next today',
                            center: 'title',
                            right: 'resourceTimelineWook, dayGridMonth,timeGridWeek'
                          }}
                          dateClick={(info) => {
                            // 日付がクリックされた時のアクション
                            const dateClicked = info.dateStr; // 'YYYY-MM-DD' 形式の日付
                            // リダイレクト先のURLを構築
                            // この例では、'/date/YYYY-MM-DD'の形式にしていますが、必要に応じて変更してください。
                            const url = `/journal-date/${dateClicked}`;
                            // リダイレクト実行
                            window.location.href = url;
                          }}
                          nowIndicator={true}
                          editable={true}
                          droppable={true}
                          selectable={true}
                          selectMirror={true}
                          initialView="dayGridMonth"
                          height="auto" // Adjust height
                          aspectRatio={1.75} // Adjust width-to-height aspect ratio
                          // Other customization options...
                        />
                    </main >
                    </div>
                </div>
            </div>
        </div>
    </>
)
}
export default Dashboard
