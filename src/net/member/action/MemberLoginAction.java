package net.member.action;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import net.member.db.MemberBean;
import net.member.db.MemberDAO;

public class MemberLoginAction implements Action {

	@Override
	   public ActionForward execute(HttpServletRequest request, HttpServletResponse response) throws Exception {
	      MemberDAO  dbc = new MemberDAO();
	      HttpSession session = request.getSession();//세션값 초기화?? 모르겠다 어쩃든 이거 써주고 
	      ActionForward forward= new ActionForward();      
	       
	      String id = request.getParameter("M_ID");//db랑 연계가 되지않음 이거 틀려서 그런건 아니겠죠?
	      String pw = request.getParameter("M_PASSWORD");//sass 세션값 한번 지워보자 
	         
	      String[] result = dbc.login(id, pw);
	         
	      if(result!=null) {
	         session.setAttribute("id", id); //세션값 저장하기~~~  형 출첵출ㅊ게
	         forward.setRedirect(true);
	            forward.setPath("./main2.rn");
	            return forward;
	      } else {
	         forward.setRedirect(true);
	            forward.setPath("./login.in");
	            return forward;
	      }
	      // 이제 바바 이제 로그인 메서드 응용해서 info에 값가지고 가면됌 
	   }

}
